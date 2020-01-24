<?php

use App\Models\Statut;
use Illuminate\Database\Seeder;

class StatutsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statut = new Statut();
        $statut->statut_name = 'Paid';
        $statut->description='the order has been paid,the order is verified and the sales manager can have his commission';
        $statut->save();
        $statut = new Statut();
        $statut->statut_name = 'Ordered';
        $statut->description='We’ve gotten your order and are preparing to send it. You still might be able to cancel the order.';
        $statut->save();
        $statut = new Statut();
        $statut->statut_name = 'Shipped';
        $statut->description='The shipping company has shipped your order';
        $statut->save();
        $statut = new Statut();
        $statut->statut_name = 'Delivered';
        $statut->description='The shipping company has delivered your order';
        $statut->save();
        $statut = new Statut();
        $statut->statut_name = 'Unable to deliver';
        $statut->description='The shipping provider wasn’t able to deliver your order, and it has been returned. Contact support to get a refund. It might take several days for your refund to appear on your statement, depending on your bank.';
        $statut->save();
        $statut = new Statut();
        $statut->statut_name = 'Canceled';
        $statut->description=' The order has been canceled.';
        $statut->save();
}
}