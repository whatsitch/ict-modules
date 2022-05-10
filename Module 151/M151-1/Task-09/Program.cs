using System.Data;
using System.Data.SqlClient;
try
{
    using (SqlConnection conn = new SqlConnection())
    {
        SqlConnectionStringBuilder builder = new SqlConnectionStringBuilder()
        {
            DataSource = @"(localdb)\MSSQLLocalDB",
            InitialCatalog = "Northwind",
            IntegratedSecurity = true,
        };
        conn.ConnectionString = builder.ConnectionString;
        
        
        String sql = "SELECT * FROM Products;" +
                     "SELECT * FROM Suppliers;" +
                     "SELECT * FROM Categories;";

        SqlDataAdapter sqlAdapter = new SqlDataAdapter(sql, conn);

        sqlAdapter.TableMappings.Add("Table", "Artikel");
        sqlAdapter.TableMappings.Add("Table1", "Lieferanten");
        sqlAdapter.TableMappings.Add("Table2", "Kategorien");

        DataSet dataSet = new DataSet();

        sqlAdapter.Fill(dataSet);

        DataTable? table = dataSet.Tables["Artikel"];

        if (table != null)
        {
            Console.WriteLine("Table: Artikel");
            Console.WriteLine("--------------");
            
            foreach (DataRow row in table.Rows)
            {
                Console.WriteLine(row["ProductName"]);
            }
        }
        
        table = dataSet.Tables["Kategorien"];
        if (table != null)
        {
            Console.WriteLine("Table: Kategorien");
            Console.WriteLine("--------------");
            
            foreach (DataRow row in table.Rows)
            {
                Console.WriteLine(row["CategoryName"]);
            } 
        }
    }
}
catch (Exception e)
{
    Console.WriteLine("connection error");
    Console.WriteLine(e);
}