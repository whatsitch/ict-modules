let endpoint = "api.php/auto";

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
      let type = $("#form-container select > option:selected").val();
      /*----- append type to form data -----*/
      formData.append("type", type);
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
                title: `Auto mit der ID ${id} wurde hinzugefügt.`,
                icon: "success",
              });
            }
            if (response.method === "PUT") {
              /*----- element updated successfully -----*/
              let id = response.id;
              Toast.fire({
                title: `Auto mit der ID ${id} wurde aktualisiert.`,
                icon: "success",
              }); /*
              Swal.fire({
                position: "top-end",
                icon: "success",
                title: `Auto mit der ID ${id} wurde aktualisiert.`,
                showConfirmButton: false,
                timer: 1500,
              });*/
            }
          } else {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: `Ein Fehler ist aufgetreten.. ${response.error}`,
            });
          }
        },
        error: function () {
          console.log("error");
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
    title = "Neues Auto hinzufügen";
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
      console.log("1");
      $.ajax({
        dataType: "json",
        type: "GET",
        url: `${endpoint}?id=${id}`,
        success: function (response) {
          console.log("response: ", response);
          if (response.success === true) {
            let template = $("#form-template").html();
            let html = Mustache.render(template, response);
            $("#form-content").html(html);
            let type = response.data[0].bauart;
            console.log("type: ", type);
            $(`#form-content select option[value="${type}"]`).prop(
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
    console.log("load table");
    $.ajax({
      type: "GET",
      url: endpoint,
      dataType: "json",
      success: function (response) {
        let table = $("#table");
        let template = $("#template").html();
        let content = Mustache.render(template, response);
        console.log("response", response);
        $(table).find("#table-content").html(content);
        $("a.edit")
          .off()
          .click(function () {
            title = "Auto Bearbeiten";
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
                    console.log("response: delete", response);
                    if (response.success === true) {
                      console.log("response true");
                      Swal.fire(
                        "Deleted!",
                        `Auto mit der ID ${id} wurde gelöscht.`,
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
        $("a.tank")
          .off()
          .click(function () {
            let id = $(this).closest("div").data("id");
            /*----- get progressbar -----*/
            var bar = document.getElementById(`${id}-js-progressbar`);

            var animate = setInterval(function () {
              bar.value += 10;

              if (bar.value >= bar.max) {
                clearInterval(animate);

                $.ajax({
                  type: `tanken`,
                  url: `${endpoint}?id=${id}`,
                  dataType: "json",
                  success: function (response) {
                    if (response.success === true) {
                      loadTable();
                    }
                  },
                });
                Swal.fire({
                  text: "Auto erfolgreich aufgetank",
                  target: "body",
                  customClass: {
                    container: "position-absolute",
                  },
                  toast: true,
                  position: "bottom-right",
                });
              }
            }, 500);
          });
      },
      error: function (error) {
        console.log("error: ", error);
      },
    });
  }
});
