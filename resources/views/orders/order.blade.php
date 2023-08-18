<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your Order') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                  <div class="p-3 mb-2">
                    <h1><b>Order # {number}</b></h1>
                    <h5>company</h5>
                    <span class="badge text-bg-success">Success</span>

                    <div class="btn-group btn-group-sm float-end" role="group" >
                      <button type="button" class="btn btn-outline-secondary">
                      
                        Re-order
                      </button>
                      <button type="button" class="btn btn-outline-secondary">
                        Print
                      </button>
                      <button type="button" class="btn btn-outline-secondary" >
                        <span class="sm material-symbols-outlined">
                          List
                        </span>
                      </button>
                      
                    </div>
                  </div>
                    <div class="card text-center">
                        <div class="card-header">
                          <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                              <a class="nav-link active" aria-current="true" href="#">Order Details</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link">Items</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link">Related Transaction</a>
                            </li>
                          </ul>
                        </div>
                        <div class="card-body" id="myTabContent">
                          <h5 class="card-title">Special title treatment</h5>
                          <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                          <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                      </div>
                   
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
