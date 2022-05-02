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

    
        conn.Open();
        Console.WriteLine("success");
        conn.Close(); 
    };

}
catch (Exception e)
{
    Console.WriteLine("database not found");
}


Console.ReadLine();