using System;
using System.Data.SqlClient;


SqlConnection conn = new SqlConnection();
SqlConnectionStringBuilder builder = new SqlConnectionStringBuilder()
{
    DataSource = @"(localdb)\MSSQLLocalDB",
    InitialCatalog = "Northwind",
    IntegratedSecurity = true,
};

conn.ConnectionString = builder.ConnectionString;

try
{
    conn.Open();
    Console.WriteLine("success");
    conn.Close();
}
catch (Exception e)
{
    Console.WriteLine("database not found");
}


Console.ReadLine();