using System;
using System.Collections.Generic;
using System.Data;
using System.Data.SqlClient;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ManuellesAktualisierenMitDataAdapter
{
    class Program
    {
        static void Main(string[] args)
        {
          string strSQL = "SELECT ProductID, ProductName, UnitPrice,  Discontinued FROM Products";
          SqlConnection con = new SqlConnection("Database=Northwind;Server=.;Integrated Security=sspi");
          SqlDataAdapter da = new SqlDataAdapter(strSQL, con);
          DataSet ds = new DataSet();
          da.Fill(ds);

          //Datenzeilen editieren
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
          newRow["ProductName"] = "Kuchen";
          newRow["UnitPrice"] = 11.30;
          newRow["Discontinued"] = 0;
          ds.Tables[0].Rows.Add(newRow);

          // Festlegen der Command-Objekte
          da.InsertCommand = CreateInsertCommand(con);
          da.UpdateCommand = CreateUpdateCommand(con);
          da.DeleteCommand = CreateDeleteCommand(con);
          da.Update(ds);
          Console.WriteLine("Aktualisierung beendet");
          Console.ReadLine();
        }

        public static SqlCommand CreateUpdateCommand(SqlConnection con)
        {
          string strSQL = "UPDATE Products SET ProductName=@Name, UnitPrice=@Preis, Discontinued=@Conti WHERE ProductID=@ID AND ProductName=@NameOld";
          SqlCommand cmd = new SqlCommand(strSQL, con);

          // die Parameter der Parameters-Auflistung hinzufügen
          SqlParameterCollection col = cmd.Parameters;
          col.Add("@Name", SqlDbType.VarChar, 40, "ProductName");
          col.Add("@Preis", SqlDbType.Money, 8, "UnitPrice");
          col.Add("@Conti", SqlDbType.Bit, 1, "Discontinued");
          col.Add("@ID", SqlDbType.Int, 4, "ProductID");
          SqlParameter param;
          param = col.Add("@NameOld", SqlDbType.VarChar, 40, "ProductName");
          param.SourceVersion = DataRowVersion.Original;
          return cmd;
        }

        public static SqlCommand CreateDeleteCommand(SqlConnection con)
        {
          string strSQL = "DELETE FROM Products WHERE ProductID=@ID";
          SqlCommand cmd = new SqlCommand(strSQL, con);
          // die Parameter der Parameters-Auflistung hinzufügen
          SqlParameter param;
          param = cmd.Parameters.Add("@ID", SqlDbType.Int,4,"ProductID");
          param.SourceVersion = DataRowVersion.Original;
          return cmd;
        }

        public static SqlCommand CreateInsertCommand(SqlConnection con)
        {
          string strSQL = "INSERT INTO Products (ProductName, UnitPrice, Discontinued) Values(@Name, @Preis, @Conti)";
          SqlCommand cmd = new SqlCommand(strSQL, con);
          // die Parameter der Parameters-Auflistung hinzufügen
          cmd.Parameters.Add("@Name", SqlDbType.VarChar, 40,"ProductName");
          cmd.Parameters.Add("@Preis", SqlDbType.Money,8, "UnitPrice");
          cmd.Parameters.Add("@Conti", SqlDbType.Bit,1,"Discontinued");
          return cmd;
        }


    }
}
