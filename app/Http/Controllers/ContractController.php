<?php

namespace App\Http\Controllers;

use App\FormatOutput\FormatOutput;
use App\Models\Contract;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ContractController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	public function pdf(Contract $contract)
	{
		$total = $contract->total;
		$data['contents'] = [
			'Upon acceptance of this contract, the CUSTOMER has agreed to purchase and hire the COMPANY to install a &#34;HOME THEATRE SYSTEM” in CUSTOMER&#39;S home at the address',
			'A list of equipments to complete the &#34;HOME THEATRE SYSTEM” (hereafter referred as &#34;SYSTEM”) including model number is enclosed in Addendum A.',
			'The CUSTOMER agrees to pay the COMPANY a total of ' . FormatOutput::dollarFormat($total) . ' for the complete SYSTEM including the equipments listed in the Addendum A, labour cost, EHF Environmental Handling Fees and applicable government tax (5% GST / 7% PST).',
			'Upon acceptance of this contract, a deposit of ' . $contract->payment_1_deposit_string . '% (' . FormatOutput::dollarFormat($total * $contract->payment_1_deposit) . ') is due. The COMPANY will coordinate with the CUSTOMER and others whom the CUSTOMER specify to set a pre-wiring installation start date.',
			'The CUSTOMER may cancel this contract at any time before midnight of the fifth business day after receiving a copy of this contract. The customer must send a signed and dated written notice of cancellation to the COMPANY. If the CUSTOMER cancel this contract within the five-day period, the CUSTOMER is entitled to a full refund of deposit. Refunds must be made within 30 days of the contractor&#39;s receipt of the cancellation notice. Otherwise, the COMPANY reserves the right to keep the deposit.',
			'The COMPANY requires a minimum of two (2) weeks notice to respond to any request for pre-wire, trim, or final installation work. The CUSTOMER shall plan or have the CUSTOMER #39S general contractor, cabinetmaker, etc coordinate their plans with the COMPANY.',
			'The CUSTOMER shall notify the contractor two (2) months prior the completion of the home or the installation date to allow the COMPANY time to purchase and prepare all the equipments listed on the Addendum A. At this time, a ' . $contract->payment_2_purchase_string . '% payment (' . FormatOutput::dollarFormat($total * $contract->payment_2_purchase) . ') is due. The COMPANY reserves the right to order and properly store the equipment prior to this date, and the CUSTOMER is not obligated to pay the COMPANY for the purchase',
			'Occasionally, there may be some changes in the equipment due to unforeseen circumstances, ie. discontinue model. The COMPANY reserves the right to upgrade the equipment, provided it is a newer model and at a higher price, without notifying the CUSTOMER. If the new model is significantly higher in cost (10% or more), the COMPANY shall notify the CUSTOMER and discuss a replacement plan. If a cheaper model is used, the COMPANY shall refund the CUSTOMER the difference. Any changes made to the Addendum A shall be mutually agreed and noted on the Addendum.',
			'A ' . $contract->payment_3_installation_string . '% (' . FormatOutput::dollarFormat($total * $contract->payment_3_installation) . ') is due prior to installation setup. And a final ' . $contract->payment_4_inspection_string . '% (' . FormatOutput::dollarFormat($total * $contract->payment_4_inspection) . ') is due on SYSTEM completion and inspection. All invoices are due when presented. Any unpaid balance will be subject to an interest charge of 3 percent (3%) per month if not paid within five (5) days of the invoice date.',
			'If the COMPANY is utilizing existing wiring, the COMPANY shall first inspect and test it. If the wire was installed by 3rd party, the COMPANY will advise the CUSTOMER regarding whether changes are necessary. Only the work performed by the COMPANY will be warranted. The COMPANY is also entitled to charge the CUSTOMER for any additional labour due to the material change of the contract.',
			'Services such as cable, satellite and fibre optics are outside of the COMPANY control. Accordingly, the COMPANY is not responsible for the cables, splitters, amplifiers, receivers or other hardware or software supplied by other service providers. If the COMPANY responds to a service call and the trouble is found to be with one of those services or the equipment supplied by non-COMPANY product, the COMPANY may bill time and materials for the service call. This includes reprogramming remotes if the channel assignments have changed.',
			'The COMPANY is not responsible for the signal quality of or reception of specific channels from cable services, fibre optic service or antenna reception such as AM, FM or Satellite (XM, Serius, etc). The COMPANY will make every effort to maximize the system for the CUSTOMER. Nonetheless, the COMPANY can not guarantee reception because these factors are beyond COMPANY&#39;s control (e.g. weather, foliage, home location, service provider quality, etc)',
			'The COMPANY can not be held responsible for the wires, speakers or other equipment being tampered with or damaged by other contractors. This will result in at least an additional charge to rectify the situation, including new equipment if necessary. It may also result in the project having to be re-designed.',
			'Unforeseen circumstances may result in a job taking longer than the allotted time. The COMPANY will make every effort to promptly complete projects but the COMPANY can not guarantee that schedule will be performed on consecutive business days from start to finish.',
			'Requests for additional work made to the installation crew that are outside the scope of the original proposal (e.g. moving furniture, hooking up additional equipment, removal of old equipment, etc) must be approved by the COMPANY and Project Manager on site, and may result in additional charges.',
			'The COMPANY employees are not permitted to move large or heavy pieces of furniture. Large or heavy TVs will only be moved to allow installation of the new equipment and then only to another location in the room. The COMPANY is not responsible for their disposal, nor any damages caused.',
			'The COMPANY provides a lifetime warranty on the wire installation. This limited warranty is void and does not apply to work that has been altered, damaged or destroyed by third parties over whom the COMPANY has no control and to work that is otherwise damaged or deteriorates as the result of accidental breakage, vandalism, and / or importer maintenance and / or improper cleaning and / or exposure to the elements. This limited warranty is made in lieu of all other warranties, express and / or implied including without limitation the warranty of merchantability and the warranty of fitness for a particular purpose, and extends only to the original purchaser. In no event, shall the COMPANY be liable for any special, consequential, indirect, incidental, or punitive damages or losses.',
			'Equipment sold and installed by the COMPANY carries the manufacturer&#39;s warranty. While it is under warranty, it will be repaired at no charge. The service call to diagnose, remove and reinstall the product is also at no charge. After three (3) months, there will be a charge, if applicable, for the service call and the time spent to diagnose, remove and reinstall the equipment. If the failure was due to faulty installation, there will be no charge.',
			'The COMPANY retains the ownership and rights to the source code for all programs that we develop.',
			'The COMPANY will at no charge to the CUSTOMER, install upgrades to software and firmware in the products we have installed if it is determined that the system is not functioning properly due to &#34;bugs” that are corrected by those upgrades. Upgrades required for adding additional performance or functionality to the system will be billed.',
			'Once the equipment is delivered to CUSTOMER&#39;s location, the CUSTOMER will be responsible for its safekeeping before and after installation. The COMPANY employees can be trusted to take the very best care of the equipment as well as your home or office. Any damage that is not due to the COMPANY&#39;s actions will be CUSTOMER&#39;S responsibility. This agreement constitutes the entire agreement between the CUSTOMER and the COMPANY and there are no agreements or representations which are not set forth herein. All prior negotiations, understandings and agreements, whether oral or written, are superseded by this agreement.',
			'The Agreement may not be changed, without the written consent of the CUSTOMER and the COMPANY.'
		];
		$pdf = PDF::loadView('pdf', $data);
		return $pdf->stream('Contract' . '.pdf');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}
}
