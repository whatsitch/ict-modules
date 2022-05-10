using System;
using ADODisconnectedSamples.NWDataSetTableAdapters;

namespace ADODisconnectedSamples
{
    class Program
    {
        static void Main(string[] args)
        {
            NWDataSet ds = new NWDataSet();
            ProductsTableAdapter tblAd = new ProductsTableAdapter();
            tblAd.Fill(ds.Products);

            foreach(NWDataSet.ProductsRow row in ds.Products)
            {
                Console.WriteLine("{0} {1}", row.ProductID, row.ProductName);
            }
            Console.ReadLine();

            NWDataSet.ProductsRow row0 = ds.Products[0];
            Console.WriteLine("vorher  {0} {1}", row0.ProductName, row0.UnitPrice);
            row0.BeginEdit();
            row0.ProductName = "Senf";
            row0.UnitPrice = 2.10M;
            row0.EndEdit();
            //row0.CancelEdit();
            Console.WriteLine("nachher {0} {1}", row0.ProductName, row0.UnitPrice);

            //tblAd.Update(row0);       //Update Row in DB
            //tblAd.Update(ds.Products);  //Update Table in DB
            //tblAd.Update(ds);           //Update DatSet in DB
            Console.ReadLine();

        }
    }
}
