<div>
    <!-- Navbar -->
    <!-- End Navbar -->

    <div class="container-fluid pb-4">
        <section>
            <ul class="breadcrumb_menu justify-content-end">
                <li id="currentDateTime" class="text-danger"></li>
            </ul>
        </section>
        @if(!in_array($user->designation, [2,12]))
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card data-card">
                    <a href="{{route('suppliers.index')}}">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl  position-absolute">
                                <i class="material-icons opacity-10">group</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Total Supplier</p>
                                <h4 class="mb-0">{{$total_suppliers}}</h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3">
                            {{-- <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+3% </span>than
                                lask month</p> --}}
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card data-card">
                    <a href="{{route('customers.index')}}">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl  position-absolute">
                                <i class="material-icons opacity-10">group</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Total Customer</p>
                                <h4 class="mb-0">{{$total_customers}}</h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3 d-flex justify-content-between align-items-center">
                            {{-- <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">-2%</span> than
                                yesterday</p> --}}
                        </div>
                    </a>
                </div>

            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card data-card">
                    <a href="{{route('admin.order.index')}}">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl  position-absolute">
                                <i class="material-icons opacity-10">shopping_cart</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Total Order</p>
                                <h4 class="mb-0">{{$total_orders}}</h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3">
                            {{-- <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+3% </span>than
                                lask month</p> --}}
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card data-card">
                    <a href="{{route('admin.order.invoice.index')}}">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl  position-absolute">
                                <i class="material-icons opacity-10">receipt_long</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Total Invoice</p>
                                <h4 class="mb-0">{{$total_invoice}}</h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3 d-flex justify-content-between align-items-center">
                            {{-- <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">-2%</span> than
                                yesterday</p> --}}
                        </div>
                    </a>
                </div>
            </div>
            {{-- Add Expense --}}
            <div class="col-xl-3 col-sm-6 mt-2">
                <div class="card data-card">
                    <a href="{{route('admin.accounting.list.depot_expense')}}">
                        <div class="card-header p-3 pt-2">
                            {{-- <div
                                class="icon icon-lg icon-shape bg-gradient-danger shadow-danger text-center border-radius-xl position-absolute">
                                <i class="material-icons opacity-10">account_balance_wallet</i>
                            </div> --}}
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Add Expense</p>
                                {{-- <h4 class="mb-0">{{$todays_expense}}</h4> --}}
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Add Payment --}}
            <div class="col-xl-3 col-sm-6 mt-2">
                <div class="card data-card">
                    <a href="{{route('admin.accounting.add_payment_receipt')}}">
                        <div class="card-header p-3 pt-2">
                            {{-- <div
                                class="icon icon-lg icon-shape bg-gradient-danger shadow-danger text-center border-radius-xl position-absolute">
                                <i class="material-icons opacity-10">account_balance_wallet</i>
                            </div> --}}
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Add Payment</p>
                                {{-- <h4 class="mb-0">{{$todays_expense}}</h4> --}}
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Add Order --}}
            <div class="col-xl-3 col-sm-6 mt-2">
                <div class="card data-card">
                    <a href="{{route('admin.order.new')}}">
                        <div class="card-header p-3 pt-2">
                            {{-- <div
                                class="icon icon-lg icon-shape bg-gradient-danger shadow-danger text-center border-radius-xl position-absolute">
                                <i class="material-icons opacity-10">account_balance_wallet</i>
                            </div> --}}
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Add Order</p>
                                {{-- <h4 class="mb-0">{{$todays_expense}}</h4> --}}
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            {{-- Add Delivery --}}
            <div class="col-xl-3 col-sm-6 mt-2">
                <div class="card data-card">
                    <a href="{{route('admin.order.index')}}">
                        <div class="card-header p-3 pt-2">
                            {{-- <div
                                class="icon icon-lg icon-shape bg-gradient-danger shadow-danger text-center border-radius-xl position-absolute">
                                <i class="material-icons opacity-10">account_balance_wallet</i>
                            </div> --}}
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Add Delivery</p>
                                {{-- <h4 class="mb-0">{{$todays_expense}}</h4> --}}
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        @endif

        @if(in_array($user->designation, [2,12]))
        {{-- <div class="row justify-content-end my-3">
            <!-- Current Wallet Balance -->
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card data-card">
                    <a href="#">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl position-absolute">
                                <i class="material-icons opacity-10">account_balance</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Current Wallet Balance</p>
                                <h4 class="mb-0">{{$wallet_balance}}</h4>
                                <h4 class="mb-0">4</h4>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div> --}}
        <div class="row">
            <!-- Monthly Collection -->
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card data-card">
                    <a href="#">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl position-absolute">
                                <i class="material-icons opacity-10">FC</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Monthly Collection</p>
                                <h4 class="mb-0">{{$monthly_collection}}</h4>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Monthly Expense -->
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card data-card">
                    <a href="#">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-warning shadow-warning text-center border-radius-xl position-absolute">
                                <i class="material-icons opacity-10">trending_down</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Monthly Expense</p>
                                <h4 class="mb-0">{{$monthly_expense}}</h4>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Today's Collection -->
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card data-card">
                    <a href="#">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl position-absolute">
                                <i class="material-icons opacity-10">FC</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Today's Collection</p>
                                <h4 class="mb-0">{{$todays_collection}}</h4>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Today's Expense -->
            <!--<div class="col-xl-3 col-sm-6">-->
            <!--    <div class="card data-card">-->
            <!--        <a href="#">-->
            <!--            <div class="card-header p-3 pt-2 mt-3">-->
            <!--                <div-->
            <!--                    class="icon icon-lg icon-shape bg-gradient-danger shadow-danger text-center border-radius-xl position-absolute">-->
            <!--                    <i class="material-icons opacity-10">account_balance_wallet</i>-->
            <!--                </div>-->
            <!--                <div class="text-end pt-1">-->
            <!--                    <p class="text-sm mb-0 text-capitalize">Today's Expense</p>-->
            <!--                    <h4 class="mb-0">{{$todays_expense}}</h4>-->
            <!--                </div>-->
            <!--            </div>-->
            <!--        </a>-->
            <!--    </div>-->
            <!--</div>-->
            
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card data-card">
                    <a href="#">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl position-absolute">
                                <i class="material-icons opacity-10">FC</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Today's Expense</p>
                                <h4 class="mb-0">{{$todays_expense}}</h4>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            {{-- Add Expense --}}
            <div class="col-xl-3 col-sm-6 mt-2">
                <div class="card data-card">
                    <a href="{{route('admin.accounting.list.depot_expense')}}">
                        <div class="card-header p-3 pt-2">
                            {{-- <div
                                class="icon icon-lg icon-shape bg-gradient-danger shadow-danger text-center border-radius-xl position-absolute">
                                <i class="material-icons opacity-10">account_balance_wallet</i>
                            </div> --}}
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Add Expense</p>
                                {{-- <h4 class="mb-0">{{$todays_expense}}</h4> --}}
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Add Payment --}}
            <div class="col-xl-3 col-sm-6 mt-2">
                <div class="card data-card">
                    <a href="{{route('admin.accounting.add_payment_receipt')}}">
                        <div class="card-header p-3 pt-2">
                            {{-- <div
                                class="icon icon-lg icon-shape bg-gradient-danger shadow-danger text-center border-radius-xl position-absolute">
                                <i class="material-icons opacity-10">account_balance_wallet</i>
                            </div> --}}
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Add Payment</p>
                                {{-- <h4 class="mb-0">{{$todays_expense}}</h4> --}}
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Add Order --}}
            <div class="col-xl-3 col-sm-6 mt-2">
                <div class="card data-card">
                    <a href="{{route('admin.order.new')}}">
                        <div class="card-header p-3 pt-2">
                            {{-- <div
                                class="icon icon-lg icon-shape bg-gradient-danger shadow-danger text-center border-radius-xl position-absolute">
                                <i class="material-icons opacity-10">account_balance_wallet</i>
                            </div> --}}
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Add Order</p>
                                {{-- <h4 class="mb-0">{{$todays_expense}}</h4> --}}
                            </div>
                        </div>
                    </a>
                </div>
            </div>
             {{-- Add Delivery --}}
            <!--<div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">-->
            <!--    <div class="card data-card">-->
            <!--        <a href="{{route('admin.order.index')}}">-->
            <!--            <div class="card-header p-3 pt-2">-->
            <!--                {{-- <div-->
            <!--                    class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl position-absolute">-->
            <!--                    <i class="material-icons opacity-10">account_balance_wallet</i>-->
            <!--                </div> --}}-->
            <!--                <div class="text-end pt-1">-->
            <!--                    <p class="text-sm mb-0 text-capitalize">Add Delivery</p>-->
            <!--                    {{-- <h4 class="mb-0">{{$todays_expense}}</h4> --}}-->
            <!--                </div>-->
            <!--            </div>-->
            <!--        </a>-->
            <!--    </div>-->
            <!--</div>-->
            
            <div class="col-xl-3 col-sm-6 mt-2">
                <div class="card data-card">
                    <a href="{{route('admin.order.index')}}">
                        <div class="card-header p-3 pt-2">
                            
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Add Delivery</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
</div>
@push('js')
<script src="{{ asset('assets') }}/js/plugins/chartjs.min.js"></script>
<script>
    function updateDateTime() {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric', 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit' 
        };
        document.getElementById("currentDateTime").innerText = now.toLocaleDateString('en-US', options);
    }

    updateDateTime(); // Call function to set initial value
    setInterval(updateDateTime, 1000); // Update every second


    
      var ctx = document.getElementById("chart-bars").getContext("2d");

      new Chart(ctx, {
          type: "bar",
          data: {
              labels: ["M", "T", "W", "T", "F", "S", "S"],
              datasets: [{
                  label: "Sales",
                  tension: 0.4,
                  borderWidth: 0,
                  borderRadius: 4,
                  borderSkipped: false,
                  backgroundColor: "rgba(255, 255, 255, .8)",
                  data: [50, 20, 10, 22, 50, 10, 40],
                  maxBarThickness: 6
              }, ],
          },
          options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                  legend: {
                      display: false,
                  }
              },
              interaction: {
                  intersect: false,
                  mode: 'index',
              },
              scales: {
                  y: {
                      grid: {
                          drawBorder: false,
                          display: true,
                          drawOnChartArea: true,
                          drawTicks: false,
                          borderDash: [5, 5],
                          color: 'rgba(255, 255, 255, .2)'
                      },
                      ticks: {
                          suggestedMin: 0,
                          suggestedMax: 500,
                          beginAtZero: true,
                          padding: 10,
                          font: {
                              size: 14,
                              weight: 300,
                              family: "Roboto",
                              style: 'normal',
                              lineHeight: 2
                          },
                          color: "#fff"
                      },
                  },
                  x: {
                      grid: {
                          drawBorder: false,
                          display: true,
                          drawOnChartArea: true,
                          drawTicks: false,
                          borderDash: [5, 5],
                          color: 'rgba(255, 255, 255, .2)'
                      },
                      ticks: {
                          display: true,
                          color: '#f8f9fa',
                          padding: 10,
                          font: {
                              size: 14,
                              weight: 300,
                              family: "Roboto",
                              style: 'normal',
                              lineHeight: 2
                          },
                      }
                  },
              },
          },
      });


      var ctx2 = document.getElementById("chart-line").getContext("2d");

      new Chart(ctx2, {
          type: "line",
          data: {
              labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
              datasets: [{
                  label: "Mobile apps",
                  tension: 0,
                  borderWidth: 0,
                  pointRadius: 5,
                  pointBackgroundColor: "rgba(255, 255, 255, .8)",
                  pointBorderColor: "transparent",
                  borderColor: "rgba(255, 255, 255, .8)",
                  borderColor: "rgba(255, 255, 255, .8)",
                  borderWidth: 4,
                  backgroundColor: "transparent",
                  fill: true,
                  data: [50, 40, 300, 320, 500, 350, 200, 230, 500],
                  maxBarThickness: 6

              }],
          },
          options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                  legend: {
                      display: false,
                  }
              },
              interaction: {
                  intersect: false,
                  mode: 'index',
              },
              scales: {
                  y: {
                      grid: {
                          drawBorder: false,
                          display: true,
                          drawOnChartArea: true,
                          drawTicks: false,
                          borderDash: [5, 5],
                          color: 'rgba(255, 255, 255, .2)'
                      },
                      ticks: {
                          display: true,
                          color: '#f8f9fa',
                          padding: 10,
                          font: {
                              size: 14,
                              weight: 300,
                              family: "Roboto",
                              style: 'normal',
                              lineHeight: 2
                          },
                      }
                  },
                  x: {
                      grid: {
                          drawBorder: false,
                          display: false,
                          drawOnChartArea: false,
                          drawTicks: false,
                          borderDash: [5, 5]
                      },
                      ticks: {
                          display: true,
                          color: '#f8f9fa',
                          padding: 10,
                          font: {
                              size: 14,
                              weight: 300,
                              family: "Roboto",
                              style: 'normal',
                              lineHeight: 2
                          },
                      }
                  },
              },
          },
      });

      var ctx3 = document.getElementById("chart-line-tasks").getContext("2d");

      new Chart(ctx3, {
          type: "line",
          data: {
              labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
              datasets: [{
                  label: "Mobile apps",
                  tension: 0,
                  borderWidth: 0,
                  pointRadius: 5,
                  pointBackgroundColor: "rgba(255, 255, 255, .8)",
                  pointBorderColor: "transparent",
                  borderColor: "rgba(255, 255, 255, .8)",
                  borderWidth: 4,
                  backgroundColor: "transparent",
                  fill: true,
                  data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
                  maxBarThickness: 6

              }],
          },
          options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                  legend: {
                      display: false,
                  }
              },
              interaction: {
                  intersect: false,
                  mode: 'index',
              },
              scales: {
                  y: {
                      grid: {
                          drawBorder: false,
                          display: true,
                          drawOnChartArea: true,
                          drawTicks: false,
                          borderDash: [5, 5],
                          color: 'rgba(255, 255, 255, .2)'
                      },
                      ticks: {
                          display: true,
                          padding: 10,
                          color: '#f8f9fa',
                          font: {
                              size: 14,
                              weight: 300,
                              family: "Roboto",
                              style: 'normal',
                              lineHeight: 2
                          },
                      }
                  },
                  x: {
                      grid: {
                          drawBorder: false,
                          display: false,
                          drawOnChartArea: false,
                          drawTicks: false,
                          borderDash: [5, 5]
                      },
                      ticks: {
                          display: true,
                          color: '#f8f9fa',
                          padding: 10,
                          font: {
                              size: 14,
                              weight: 300,
                              family: "Roboto",
                              style: 'normal',
                              lineHeight: 2
                          },
                      }
                  },
              },
          },
      });

</script>
@endpush