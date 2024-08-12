@extends('User.layouts.app')





@section('content')


<main class="main-dashboard">
        <section class="dashboard">
            <div class="submit_section">
                <div class="date">
                    <form class="search-form-box">
                        <input type="date" id="calender" name="calender" placeholder="select date">
                        <input type="date" id="calender" name="calender">
                        <button class="main-btn search-btn">Search</button>
                    </form>
                </div>
            </div>
            <div class="income-section">
                <div class="earn-box total-sales">
                    <div class="eraning-left">
                        <h3>Total Sales</h3>
                        <h1>$ 8990.00</h1>

                    </div>
                    <div class="eraning-right">
                        <div class="monthly-detail">
                            <div class="total-sales-icon">
                                <img src="{{asset('Admin/images/sale-icon-1.svg')}}">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="earn-box total-categories">
                    <a href="javascript: void(0)">
                        <h2 class="heading-main">24</h2>
                        <p>Total Categories</p>
                    </a>

                </div>
                <div class="earn-box total-products">
                    <a href="javascript: void(0))">
                        <h2 class="heading-main">5146</h2>
                        <p>Total Products</p>
                    </a>
                </div>
            </div>

            <div class="recent-sales">
                <h2 class="font-26">Recent Sales</h2>
                <div class="main-table">
                    <table>
                        <tbody>
                            <tr class="heading">
                                <th>Sr. No.</th>
                                <th>Name of product</th>
                                <th>Mode of purchase</th>
                                <th>Price</th>
                            </tr>
                            <tr>
                                <td>#0032</td>
                                <td>Assorted Artificial Plants</td>
                                <td>
                                    <span style="color: #34A853;">Online</span>
                                </td>
                                <td>$25.99</td>
                            </tr>
                            <tr>
                                <td>#0032</td>
                                <td>Agaves & Artificial Cycads</td>
                                <td>
                                    <span style="color: #0F5132;">Offline</span>
                                </td>
                                <td>$25.99</td>
                            </tr>
                            <tr>
                                <td>#0032</td>
                                <td>Artificial Ferns</td>
                                <td> <span style="color: #34A853;">Online</span></td>
                                <td>$25.99</td>
                            </tr>
                            <tr>
                                <td>#0032</td>
                                <td>Artificial Fruit Trees</td>
                                <td> <span style="color: #0F5132;">Offline</span></td>
                                <td>$25.99</td>
                            </tr>
                            <tr>
                                <td>#0032</td>
                                <td>Artificial Trees</td>
                                <td> <span style="color: #34A853;">Online</span></td>
                                <td>$25.99</td>
                            </tr>
                            <tr>
                                <td>#0032</td>
                                <td>Bamboo Artificial Trees</td>
                                <td> <span style="color: #0F5132;">Offline</span></td>
                                <td>$25.99</td>
                            </tr>
                            <tr>
                                <td>#0032</td>
                                <td>Bonsai artificial trees</td>
                                <td> <span style="color: #34A853;">Online</span></td>
                                <td>$25.99</td>
                            </tr>
                            <tr>
                                <td>#0032</td>
                                <td>Dracaena & Artificial Yucca</td>
                                <td> <span style="color: #0F5132;">Offline</span></td>
                                <td>$25.99</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>   




@endsection