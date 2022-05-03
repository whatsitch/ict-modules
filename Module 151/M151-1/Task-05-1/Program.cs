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
        
        const string sql = "UPDATE PRODUCTS SET ProductName = 'Sojasauce' WHERE ProductName = 'Chai'";

        SqlCommand command = new SqlCommand(sql, conn);

        conn.Open();
        if (command.ExecuteNonQuery() > 0)
            Console.WriteLine("dataset successfully updated");
    }
}
catch (Exception e)
{
    Console.WriteLine("database not found");
    Console.WriteLine(e);
}


Console.ReadLine();