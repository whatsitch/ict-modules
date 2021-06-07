let endpoint = "api.php/immobilien";

const Toast = Swal.mixin({
  toast: true,
  position: "top-end",
  showConfirmButton: false,
  timer: 5000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener("mouseenter", Swal.stopTimer);
    toast.addEventListener("mouseleave", Swal.resumeTimer);
  },
});

$("document").ready(function () {
  loadTable();

  $("#form-container").load("./components/Formular.html", function () {
    $("#form-container").submit(function (event) {
      event.preventDefault();
      let formData = new FormData(event.target);
      /*----- get selected value-----*/
      let kategorie = $("#form-container select > option:selected").val();
      /*----- append kategory to form data -----*/
      formData.append("kategorie", kategorie);
      let data = Object.fromEntries(formData.entries());

      /*----- get id of element if exist -----*/
      let id = parseInt($("#id").val()) === -1 ? 0 : parseInt($("#id").val());

      $.ajax({
        dataType: "json",
        data: data,
        type: "POST",
        url: `${endpoint}?id=${id}`,
        success: function (response) {
          if (response.success === true) {
            loadTable();
            /*----- hide modal -----*/
            UIkit.modal("#modal").hide();

            if (response.method === "POST") {
              /*----- element added successfully -----*/
              let id = response.id;
              Toast.fire({
                title: `Immobilie erfolgreich hinzugefügt`,
                icon: "success",
              });
            }
            if (response.method === "PUT") {
              /*----- element updated successfully -----*/
              let id = response.id;
              Toast.fire({
                title: `Immobilie wurde erfolgreich aktualisiert.`,
                icon: "success",
              });
            }
          } else {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: `Ein Fehler ist aufgetreten.. ${response.error}`,
            });
          }
        },
        error: function (error) {
          /*----- error occured -----*/
          UIkit.modal("#modal").hide();
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Ein Fehler ist aufgetreten.. Bitte erneut versuchen",
          });
        },
      });
    });
  });

  /*----- new entry button click event -----*/
  $("#new-entry a").click(function () {
    title = "Neue Immobilie hinzufügen";
    $("#modal .uk-modal-header h2").text(title);
    fillEdit(-1);
    UIkit.modal("#modal").show();
  });

  /**
   * fill form with existing element data
   * @param {*} id element id
   */
  function fillEdit(id) {
    if (id >= 0) {
      $.ajax({
        dataType: "json",
        type: "GET",
        url: `${endpoint}?id=${id}`,
        success: function (response) {
          if (response.success === true) {
            let template = $("#form-template").html();
            let html = Mustache.render(template, response);
            $("#form-content").html(html);
            let category = response.data[0].kategorie;
            $(`#form-content select option[value="${category}"]`).prop(
              "selected",
              true
            );
          }
        },
      });
    } else {
      let response = {
        data: {
          id: -1,
        },
      };
      let template = $("#form-template").html();
      let html = Mustache.render(template, response);
      $("#form-content").html(html);
    }
  }

  /**
   * call endpoint and retrieve every element
   */
  function loadTable() {
    $.ajax({
      type: "GET",
      url: endpoint,
      dataType: "json",
      success: function (response) {
        let table = $("#table");
        let template = $("#template").html();

        /*----- verify if object is active -----*/
        response.data.forEach((object) => {
          if (object["status"] == 1) {
            object.active = true;
          }
        });
        let content = Mustache.render(template, response);
        $(table).find("#table-content").html(content);
        $("a.edit")
          .off()
          .click(function () {
            title = "Immobilie bearbeiten";
            $("#modal .uk-modal-header h2").text(title);
            fillEdit($(this).closest("div").data("id"));
            UIkit.modal("#modal").show();
          });
        $("a.delete")
          .off()
          .click(function () {
            Swal.fire({
              title: "Wirklich löschen?",
              text: "Dies kann nicht rückgängig gemacht werden.",
              icon: "warning",
              showCancelButton: true,
              confirmButtonColor: "#3085d6",
              cancelButtonColor: "#d33",
              confirmButtonText: "Löschen",
              cancelButtonText: "abbrechen",
            }).then((result) => {
              if (result.isConfirmed) {
                let id = $(this).closest("div").data("id");
                $.ajax({
                  type: "DELETE",
                  url: `${endpoint}/?id=${id}`,
                  dataType: "json",
                  success: function (response) {
                    loadTable();
                    if (response.success === true) {
                      Swal.fire(
                        "Deleted!",
                        `Immobilie wurde erfolgreich gelöscht.`,
                        "success"
                      );
                    } else {
                      Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text:
                          "Ein Fehler ist aufgetreten.. Bitte erneut versuchen",
                      });
                    }
                  },
                  error: function () {
                    Swal.fire({
                      icon: "error",
                      title: "Oops...",
                      text:
                        "Ein Fehler ist aufgetreten.. Bitte erneut versuchen",
                    });
                  },
                });
              }
            });
          });
        $("a.immo-checkbox")
          .off()
          .click(function (event) {
            if (event.target.tagName == "DIV") {
              return;
            }
            let id = $(this).closest("div").data("id");
            let status = $(this).closest(".status-checkbox");
            let active;
            $(`input[name='checkbox-${id}']:checked`).each(function () {
              active = true;
            });
            let method = active ? "activate" : "deactivate";
            $.ajax({
              type: method,
              url: `${endpoint}?id=${id}`,
              dataType: "json",
              success: function (response) {
                if (response.success === true) {
                  loadTable();

                  if (response.active === true) {
                    Toast.fire({
                      title: `Immobilie wurde erfolgreich aktiviert.`,
                      icon: "success",
                    });
                  }
                  if (response.active === false) {
                    Toast.fire({
                      title: `Immobilie wurde erfolgreich deaktiviert.`,
                      icon: "success",
                    });
                  }
                }
              },
            });
          });
      },
      error: function (error) {},
    });
  }
});
