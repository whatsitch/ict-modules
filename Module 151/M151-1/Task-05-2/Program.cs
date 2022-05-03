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

        const string sql = "INSERT INTO PRODUCTS(ProductName, Discontinued) Values('Schweizer Käse', 0) ";
        SqlCommand command = new SqlCommand(sql, conn);

        conn.Open();
        if (command.ExecuteNonQuery() > 0)
            Console.WriteLine("dataset successfully inserted");
    }
}
catch (Exception e)
{
    Console.WriteLine("database not found");
    Console.WriteLine(e);
}


Console.ReadLine();