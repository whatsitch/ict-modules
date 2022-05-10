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
            DataSet ds = new DataSet();
            da.Fill(ds);
            // Anzeige der Daten im lokalen Speicher
            for (int i = 0; i < ds.Tables[0].Rows.Count; i++)
            {
                DataRow row = ds.Tables[0].Rows[i];
                Console.WriteLine("{0,-35} {1} ", row[0], row[1]);
            }
            Console.ReadLine();
        }
    }
}
