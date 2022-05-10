using System;
using System.Data;
using System.Data.SqlClient;

namespace DataAdapterSample
{
    class Program
    {
        static void Main(string[] args)
        {
            SqlConnection con = new SqlConnection(@"Data Source=.;Database=Northwind;Integrated security=True");
            string strSQL = "SELECT ProductName, UnitPrice " +
                  "FROM Products WHERE UnitsOnOrder > 0";
      
            SqlDataAdapter da = new SqlDataAdapter(strSQL, con);
            DataSet ds = new DataSet();
            da.Fill(ds, "Artikel");

            // Anzeige der Daten im lokalen Speicher
            DataTable tbl = ds.Tables["Artikel"];
            for (int i = 0; i < tbl.Rows.Count; i++)
            {
                DataRow row = tbl.Rows[i];
                Console.WriteLine("{0,-35} {1} ", row["ProductName"], row["UnitPrice"]);
            }
            Console.ReadLine();
        }
    }
}
