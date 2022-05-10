using System;
using System.Collections.Generic;
using System.Data;
using System.Data.SqlClient;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ManuelleAktualisierungMitExecuteNonQuery
{
    class Program
    {
        static void Main(string[] args)
        {
          string strSQL = "SELECT ProductID, ProductName, UnitPrice, Discontinued FROM Products";
          string strCon = "Database=Northwind;Server=.;Integrated Security=sspi";
          SqlConnection con = new SqlConnection(strCon);
          SqlDataAdapter da = new SqlDataAdapter(strSQL, con);
          DataSet ds = new DataSet();
          da.Fill(ds);

          // Festlegen der Command-Objekte
          SqlCommand cmdInsert = GetInsertCommand(con);
          SqlCommand cmdUpdate = GetUpdateCommand(con);
          SqlCommand cmdDelete = GetDeleteCommand(con);

          // Datenzeilen editieren
          foreach (DataRow row in ds.Tables[0].Rows)
          {
            if (row["ProductName"].ToString() == "Chai")
              row["ProductName"] = "Kuchen";
          }

          // Datenzeile hinzufügen
          ds.Tables[0].Columns[0].AutoIncrement = true;
          ds.Tables[0].Columns[0].AutoIncrementSeed = -1;
          ds.Tables[0].Columns[0].AutoIncrementStep = -1;
          DataRow newRow = ds.Tables[0].NewRow();
          newRow["ProductName"] = "Camembert";
          newRow["UnitPrice"] = 10;
          newRow["Discontinued"] = 0;
          ds.Tables[0].Rows.Add(newRow);

          // Definition einer Variablen vom Typ DataViewRowState, die alle zu berücksichtigenden Änderungen beschreibt
          DataViewRowState drvs = DataViewRowState.Added | DataViewRowState.Deleted | DataViewRowState.ModifiedCurrent;

          // geänderte Datenzeilen in die Datenquelle schreiben
          int countDS = 0;
          con.Open();
          foreach (DataRow row in ds.Tables[0].Select("", "", drvs))
          {
            switch (row.RowState)
            {
              case DataRowState.Added:
                Console.Write("DS hinzufügen...");
                countDS = SubmitAddedRow(cmdInsert, row);
                break;
              case DataRowState.Deleted:
                Console.Write("DS löschen...");
                countDS = SubmitDeletedRow(cmdDelete, row);
                break;
              case DataRowState.Modified:
                Console.Write("DS ändern...");
                countDS = SubmitUpdatedRow(cmdUpdate, row);
                break;
            }
            if (countDS == 1){
              Console.WriteLine("erfolgreich");
              row.AcceptChanges();
            }
            else
            {
              Console.WriteLine("mißlungen");
              row.RowError = "Eine Änderung wurde nicht akzeptiert";
            }
          }
          Console.WriteLine("Datenbank wurde aktualisiert");

          con.Close();
          Console.ReadLine();
        }

        static int SubmitUpdatedRow(SqlCommand cmd, DataRow row)
        {
          // Parameter füllen
          cmd.Parameters["@Name"].Value = row["ProductName"];
          cmd.Parameters["@Preis"].Value = row["UnitPrice"];
          cmd.Parameters["@Conti"].Value = row["Discontinued"];
          cmd.Parameters["@ID"].Value = row["ProductID"];
          cmd.Parameters["@NameOld"].Value = row["ProductName", DataRowVersion.Original];
          // Anzahl der betroffenen Zeilen zurückliefern
          return cmd.ExecuteNonQuery();
        }



        // DeleteCommand erzeugen
        static SqlCommand GetDeleteCommand(SqlConnection con)
        {
          string strSQL = "DELETE FROM Products WHERE ProductID=@ID";
          SqlCommand cmd = new SqlCommand(strSQL, con);
          // die Parameter der Parameters-Auflistung hinzufügen
          cmd.Parameters.Add("@ID", SqlDbType.Int, 4);
          return cmd;
        }

        // InsertCommand erzeugen
        static SqlCommand GetInsertCommand(SqlConnection con)
        {
          string strSQL = "INSERT INTO Products (ProductName, UnitPrice, Discontinued) Values(@Name, @Preis, @Conti)";
          SqlCommand cmd = new SqlCommand(strSQL, con);
          // die Parameter der Parameters-Auflistung hinzufügen
          cmd.Parameters.Add("@Name", SqlDbType.VarChar, 40);
          cmd.Parameters.Add("@Preis", SqlDbType.Money, 8);
          cmd.Parameters.Add("@Conti", SqlDbType.Bit,1);
          return cmd;
        }

        static int SubmitDeletedRow(SqlCommand cmd, DataRow row)
        {
          // Parameter füllen
          cmd.Parameters["@ID"].Value = row["ProductID"];
          // Anzahl der betroffenen Zeilen zurückliefern
          return cmd.ExecuteNonQuery();
        }

        static int SubmitAddedRow(SqlCommand cmd, DataRow row)
        {
          // Parameter füllen
          cmd.Parameters["@Name"].Value = row["ProductName"];
          cmd.Parameters["@Preis"].Value = row["UnitPrice"];
          cmd.Parameters["@Conti"].Value = row["Discontinued"];
          // Anzahl der betroffenen Zeilen zurückliefern
          return cmd.ExecuteNonQuery();
        }

        // UpdateCommand erzeugen
        static SqlCommand GetUpdateCommand(SqlConnection con)
        {
          string strSQL = "UPDATE Products SET ProductName=@Name, UnitPrice=@Preis, Discontinued=@Conti WHERE ProductID=@ID AND ProductName=@NameOld";
          SqlCommand cmd = new SqlCommand(strSQL, con);
          // die Parameter der Parameters-Auflistung hinzufügen
          SqlParameterCollection col = cmd.Parameters;
          col.Add("@Name", SqlDbType.VarChar, 40, "ProductName");
          col.Add("@Preis", SqlDbType.Money,8, "UnitPrice");
          col.Add("@Conti", SqlDbType.Bit,1, "Discontinued");
          col.Add("@ID", SqlDbType.Int, 4, "ProductID");
          SqlParameter param;
          param = col.Add("@NameOld", SqlDbType.VarChar, 40, "ProductName");
          param.SourceVersion = DataRowVersion.Original;
          return cmd;
        }

    }
}
