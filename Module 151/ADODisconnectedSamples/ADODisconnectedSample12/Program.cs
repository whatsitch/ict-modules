using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using ADODisconnectedSample12.NWDataSet2TableAdapters;


namespace ADODisconnectedSample12
{
    class Program
    {
        static void Main(string[] args)
        {
            NWDataSet2 ds = new NWDataSet2();
            ProductsTableAdapter prodTA = new ProductsTableAdapter();
            prodTA.Fill(ds.Products);

            NWDataSet2.ProductsRow row = ds.Products[0];
            Console.WriteLine("vorher {0}", row.ProductName);
            row.BeginEdit();
            row.ProductName = "Trester";
            row.EndEdit();
            Console.WriteLine("nachher {0}", row.ProductName);

            prodTA.Update(ds); //Exception: 

        }
    }
}
