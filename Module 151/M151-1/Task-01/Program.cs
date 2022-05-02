using System;
using System.Data.SqlClient;

Console.WriteLine("Hello, World!");

SqlConnection conn = new SqlConnection();

conn.ConnectionString = 
    @"Data Source = (localdb)\MSSQLLocalDB; 
    Initial Catalog = Northwind; 
    Integrated Security = True";

conn.Open();
Console.WriteLine("connection opened");
conn.Close();
Console.WriteLine("connection closed");


Console.ReadLine();