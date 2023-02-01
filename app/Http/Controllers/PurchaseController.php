<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PaymentSupplier;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Coin;

use \Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Traits\FiltersTrait;
use App\Traits\GetDataCommonTrait;
use App\Traits\CalculateMountsTrait;
use App\Facades\DataCommonFacade;
use App\Facades\PurchaseFacade;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;

use PDF;

class PurchaseController extends Controller
{
    use  FiltersTrait, GetDataCommonTrait, CalculateMountsTrait;

    public function __construct() {
        $this->middleware('role');
    }
    // public function load_purchases($filter) {
    //     if (count($filter) ==0)
    //         return Purchase::select('purchases.*','purchases.purchase_date as date')->orderBy('purchase_date','desc')->orderBy('created_at','desc');
    //     else
    //         return Purchase::select('purchases.*','purchases.purchase_date as date')->where($filter)->orderBy('purchase_date','desc')->orderBy('created_at','desc');
    // }

    public function index()
    {
        $purchases = Purchase::getPurchases()->get();
        $data = ['data_filter' => $this->data_filter([]),'header' => 'Facturas de Compras' ];
        $data_common = DataCommonFacade::index('Purchase',$data);
        return view('purchases.index',compact('purchases','data_common'));
        //
    }

    public function create()
    {
        $data = $this->generate_data_coin('calc_currency_purchase');
        $data['products'] = Product::GetProducts()->get();
        $data['suppliers'] = Supplier::GetSuppliers()->get();
        $data['coins'] = $this->get_coins_invoice_payment($this->get_base_coin_rate($data['calc_coin']->id),'calc_currency_purchase')->get();
        // la linea de abajo es si se queire poner los Bs para cuando el calculo sean en otra moneda no lo uso no me parece. la tasa depende del proveedor
        // $message_balance .= ($calc_coin->symbol != $base_coin->symbol ? ' - '.number_format($movements[0]->balance * $rate->sale_price,2).$base_coin->symbol:'');
        $data = PurchaseFacade::getData();
        // $data['header'] = 'Creando Factura de Compra';
        $data_common = DataCommonFacade::create('Purchase',$data);
        return view('purchases.create',compact('data','data_common'));
    }

    public function store(StorePurchaseRequest $request)
    {
        $continue = true;
        $exist_purchase = Purchase::where('supplier_id',$request->supplier_id)->where('mount',$request->mount)
            ->where('purchase_date',$request->purchase_date)->first();
        if ($exist_purchase != '') {
            $fecha1 = new Carbon($exist_purchase->created_at);
            if ($fecha1->diffInMinutes(now()) < 5)
                $continue = false;
        }
        // return PurchaseFacade::storePurchase($request);
        return redirect()->route('purchases.index')->with('message_status',$continue ? PurchaseFacade::storePurchase($request)
                        : 'Error_Registro ya existente espere 5 minuto para intentar nuevamente');
    }

    public function show($id)
    {

        // $data = $this->generate_data_coin('calc_currency_purchase');
        // $data['invoice'] = Purchase::with('Supplier','Coin','PurchaseDetails','PurchaseDetails.Product')->where('id',$id)->first();
        // $data['coins'] = array($data['invoice']->coin);
        // // $coins = $this->get_coins_invoice_payment($this->get_base_coin_rate($data['calc_coin']->id),'calc_currency_purchase')->get();
        // $mount_other = ($data['calc_coin']->id != $data['base_coin']->coin_id ? $this->mount_other($data['invoice'],$data['calc_coin']) : 0);
        // $data['mount_other'] = $mount_other;
        // $data['header'] = 'Detalle Factura de Compra';
        // $data['suppliers'] = Supplier::where('id',$data['invoice']->supplier_id)->get();
        $data = PurchaseFacade::getData($id);
        $data_common = DataCommonFacade::edit('Purchase',$data);
        return view('purchases.show',compact('data','data_common'));
    }

///// El destroy anula una factura
    public function destroy($id)
    {
        DB::beginTransaction();
        $purchase = Purchase::with('Supplier','Coin')->find($id);
        $message="";
        if ($purchase->conditions == "Credito") {
            $calc_coin = $this->get_base_coin('calc_currency_sale')->first();
            $mount = $purchase->coin_id != $calc_coin->id ? $this->mount_other($purchase,$calc_coin) : $purchase->mount;
            Supplier::where('id',$purchase->supplier->id)->update(['balance' => $purchase->supplier->balance - $mount]);
            $message = "Actualizado Balance del Cliente";
        }
        // if ($purchase->conditions == "Credito") {
            // $data = $this->generate_data_coin('calc_currency_purchase');
            // if ($data['calc_coin']->id == $purchase->coin_id)
            //     $mount = $purchase->mount;
            // else {
            //     $mount = ($data['base_coin']->id == $purchase->coin_id ? $purchase->mount * $purchase->rate_exchange
            //                         : $purchase->mount / $purchase->rate_exchange );
/// aqui no creo sea $data['rate'], sino la tasa de la factura de compra
            // }
        //     $supplier = Supplier::find($purchase->supplier_id);
        //     $message = "Actualizado Balance del Proveedor ".$supplier->name;
        // }
        Purchase::where('id',$purchase->id)->update(['status' => 'Anulada']);
        DB::commit();
        return redirect()->route('purchases.index')->with('message_status',"Factura de Compra del Proveedor: ".$purchase->Supplier->name
                ." por un monto de: ".$purchase->mount." ".$purchase->Coin->symbol." Anulada con exito. ".$message);

    }

    public function filter(Request $request) {
        $filter = $this->create_filter($request,'purchase_date');
        $purchases = Purchase::GetPurchases($filter)->get();
        $data = ['data_filter' => $this->data_filter($filter), 'header' => 'Facturas de Compras' ];
        $data_common = DataCommonFacade::index('Purchase',$data);
        if ($request->option == "Report") {
            $pdf = PDF::loadView('shared.payment-list-report',['models' => $purchases,'data_common'=> $data_common]);
            return $pdf->stream();
        }
        else {
            return view('purchases.index',compact('purchases','data_common'));
        }
    }
}
