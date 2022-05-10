using System;
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

        const string sql = "DELETE FROM PRODUCTS WHERE ProductName = 'Schweizer Käse' ";
        SqlCommand command = new SqlCommand(sql, conn);

        conn.Open();
        if (command.ExecuteNonQuery() > 0)
            Console.WriteLine("dataset successfully deleted");
    }
}
catch (Exception e)
{
    Console.WriteLine("database not found");
    Console.WriteLine(e);
}


Console.ReadLine();