@extends('layouts.master-cashier')

@section('body')
<div class="container-fluid cashier-body">
    <div class="row">
        <!-- Left: Menu -->
        <div class="col-md-12">
            <!-- Category Buttons -->
            @php
                $categories = [
                    ['label' => 'Hot', 'icon' => 'fas fa-fire'],
                    ['label' => 'Burger', 'icon' => 'fas fa-hamburger'],
                    ['label' => 'Pizza', 'icon' => 'fas fa-pizza-slice'],
                    ['label' => 'Snack', 'icon' => 'fas fa-box'],
                    ['label' => 'Soft Drink', 'icon' => 'fas fa-cocktail'],
                    ['label' => 'Coffee', 'icon' => 'fas fa-mug-hot'],
                    ['label' => 'Ice Cream', 'icon' => 'fas fa-ice-cream'],
                ];
            @endphp

            <div class="d-flex flex-wrap gap-2 mb-3">
                @foreach($categories as $index => $category)
                    <button class="category-btn {{ $index === 0 ? 'active' : '' }}">
                        <i class="{{ $category['icon'] }}"></i>
                        <div class="label">{{ $category['label'] }}</div>
                    </button>
                @endforeach
            </div>


        <!-- Scrollable Menu Items -->
        <div class="menu-scroll">
            <div class="row"> 
                @for($i = 0; $i < 30; $i++)
                <div class="col-12 col-md-4 col-lg-3 mb-4">
                    <div class="menu-item">
                        <img src="{{ asset('template/img/burger.png') }}" alt="Food">
                        <div class="mt-2 font-weight-bold">
                             {{ ['Pizza', 'Chicken', 'Burger', 'Chips'][$i % 4] }}
                        </div>
                        <button class="btn btn-sm btn-primary primary-radius w-100 mt-2 button-price d-flex justify-content-between align-items-center">
                            <span>₱300.00</span>
                            <span class="circle-icon bg-white text-primary rounded-circle d-flex justify-content-center align-items-center" style="width: 24px; height: 24px;">
                                <i class="fas fa-plus"></i>
                            </span>
                        </button>
                    </div>
                </div>
                @endfor
            </div>
        </div>


        </div>
    </div>
</div>
@endsection

