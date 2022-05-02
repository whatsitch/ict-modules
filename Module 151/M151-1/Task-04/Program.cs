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
            Pooling = true
        };

        conn.ConnectionString = builder.ConnectionString;


        for (int i = 0; i < 10; i++)
        {
            conn.Open();
            Console.WriteLine("connection {0} opened", i);
            conn.Close(); 
            Console.WriteLine("connection {0} closed", i);

        }



    };

}
catch (Exception e)
{
    Console.WriteLine("database not found");
}


Console.ReadLine();