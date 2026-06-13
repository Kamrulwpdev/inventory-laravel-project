<div class="container py-5">
    <div class="row">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm p-4">
                <h4 class="fw-bold mb-4">Billing Details</h4>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Full Name</label>
                        <input type="text" wire:model="customer_name" class="form-control" placeholder="John Doe">
                        @error('customer_name') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Phone Number</label>
                        <input type="text" wire:model="customer_phone" class="form-control" placeholder="017xxxxxxxx">
                        @error('customer_phone') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Delivery Address</label>
                        <textarea wire:model="customer_address" class="form-control" rows="3"></textarea>
                        @error('customer_address') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
           <div class="card border-0 shadow-sm p-4 bg-light">
            <h4 class="fw-bold mb-4">Your Order</h4>
            
            <ul class="list-group list-group-flush mb-3">
                @foreach($cartItems as $item)
                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                    <div>
                        <h6 class="my-0">{{ $item['name'] }}</h6>
                        <small class="text-muted">Quantity: {{ $item['quantity'] }}</small>
                    </div>
                    <span class="text-muted">${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                </li>
                @endforeach
        
                <li class="list-group-item bg-transparent px-0 py-3">
                    <label class="form-label fw-bold small uppercase">Delivery Area</label>
                    <select wire:model.live="delivery_charge" class="form-select form-select-sm">
                        <option value="0">Select Area</option>
                        <option value="50">Inside Dhaka ($50.00)</option>
                        <option value="150">Outside Dhaka ($150.00)</option>
                    </select>
                </li>
        
                <li class="list-group-item d-flex justify-content-between px-0 border-0">
                    <span>Subtotal</span>
                    <span>${{ number_format($cartTotal - $delivery_charge, 2) }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0 border-0">
                    <span>Delivery Charge</span>
                    <span>${{ number_format($delivery_charge, 2) }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0 mt-2">
                    <strong class="fs-5">Total</strong>
                    <strong class="fs-5 text-primary">${{ number_format($cartTotal, 2) }}</strong>
                </li>
            </ul>
        
            <button wire:click="placeOrder" 
                    class="btn btn-success btn-lg w-100 fw-bold shadow-sm"
                    {{ $delivery_charge == 0 ? 'disabled' : '' }}>
                Confirm & Place Order
            </button>
            @if($delivery_charge == 0)
                <small class="text-danger d-block text-center mt-2">Please select a delivery area</small>
            @endif
        </div>
        </div>
    </div>
</div>