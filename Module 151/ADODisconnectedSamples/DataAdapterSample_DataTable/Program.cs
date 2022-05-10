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
            string strSQL = "SELECT ProductName, UnitPrice FROM products";
            SqlDataAdapter da = new SqlDataAdapter(strSQL, con);
            DataTable tbl = new DataTable();
            da.Fill(tbl);
            // Anzeige der Daten im lokalen Speicher
            for (int i = 0; i < tbl.Rows.Count; i++)
            {
                DataRow row = tbl.Rows[i];
                Console.WriteLine("{0,-35} {1} ", row[0], row[1]);
            }
            Console.ReadLine();
        }
    }
}
