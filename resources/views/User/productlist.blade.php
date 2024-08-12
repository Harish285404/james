@extends('User.layouts.app')


@section('content')
    <main class="main-dashboard">
        <section class="categories-list product-list">
            <div class="recent-sales">
                <div class="input-search-box-container">
                    <h2 class="font-26">Products</h2>
                    <input type="text" id="Search-bar" placeholder="Search">
                </div>
                <div class="main-table">
                    <table>
                        <tbody>
                            <tr class="heading">
                                <th>Sr. No.</th>
                                <th>Name of product</th>
                                <th>Status</th>
                                <th>Sale Price</th>
                                <th>Retail Price</th>
                                <th>Action</th>
                            </tr>
                            <tr>
                                <td>#0038</td>
                                <td>Assorted Artificial Plants</td>
                                <td>
                                    <span style="color: #34A853;">Live</span>
                                </td>
                                <td>$20.99</td>
                                <td>$25.99</td>
                                <td class="action-btns">
                          
                                 <a href="{{url('single-product')}}" class="View-btn">View</a>
                                </td>
                            </tr>
                            <tr>
                                <td>#0033</td>
                                <td>Aquarium & Reptile Artificial Plants</td>
                                <td>
                                    <span style="color: #F7B36B;">Hide</span>
                                </td>
                                <td>$21.99</td>
                                <td>$25.99</td>
                                <td class="action-btns">
                                  <a href="{{url('single-product')}}" class="View-btn">View</a>
                                </td>
                            </tr>
                            <tr>
                                <td>#0034</td>
                                <td>Artificial Ferns</td>
                                <td>
                                    <span style="color: #F7B36B;">Hide</span>
                                </td>
                                <td>$24.99</td>
                                <td>$25.99</td>
                                <td class="action-btns">
                                    <a href="{{url('single-product')}}" class="View-btn">View</a>
                                </td>
                            </tr>
                            <tr>
                                <td>#0035</td>
                                <td>Artificial Fruit Trees</td>
                                <td>
                                    <span style="color: #34A853;">Live</span>
                                </td>
                                <td>$18.99</td>
                                <td>$25.99</td>
                                <td class="action-btns">
                            <a href="{{url('single-product')}}" class="View-btn">View</a>
                                </td>
                            </tr>
                            <tr>
                                <td>#0036</td>
                                <td>Artificial Palms</td>
                                <td>
                                    <span style="color: #34A853;">Live</span>
                                </td>
                                <td>$19.99</td>
                                <td>$25.99</td>
                                <td class="action-btns">
                                <a href="{{url('single-product')}}" class="View-btn">View</a>
                                </td>
                            </tr>
                            <tr>
                                <td>#0037</td>
                                <td>Bamboo Artificial Trees</td>
                                <td>
                                    <span style="color: #34A853;">Live</span>
                                </td>
                                <td>$20.99</td>
                                <td>$25.99</td>
                                <td class="action-btns">
                               <a href="{{url('single-product')}}" class="View-btn">View</a>
                                </td>
                            </tr>
                            <tr>
                                <td>#0038</td>
                                <td>Cactus & Artificial Succulents</td>
                                <td>
                                    <span style="color: #34A853;">Live</span>
                                </td>
                                <td>$20.99</td>
                                <td>$25.99</td>
                                <td class="action-btns">
                         <a href="{{url('single-product')}}" class="View-btn">View</a>
                                </td>
                            </tr>
                            <tr>
                                <td>#0039</td>
                                <td>Ficus Trees</td>
                                <td>
                                    <span style="color: #F7B36B;">Hide</span>
                                </td>
                                <td>$20.99</td>
                                <td>$25.99</td>
                                <td class="action-btns">
                           <a href="{{url('single-product')}}" class="View-btn">View</a>
                                </td>

                            </tr>
                            <tr>
                                <td>#0040</td>
                                <td>Hanging Wall Planters</td>
                                <td>
                                    <span style="color: #34A853;">Live</span>
                                </td>
                                <td>$18.99</td>
                                <td>$21.99</td>
                                <td class="action-btns">
                            <a href="{{url('single-product')}}" class="View-btn">View</a>
                                </td>
                            </tr>
                            <tr>
                                <td>#0041</td>
                                <td>Floral Arrangements</td>
                                <td>
                                    <span style="color: #F7B36B;">Hide</span>
                                </td>
                                <td>$23.99</td>
                                <td>$25.99</td>
                                <td class="action-btns">
                  <a href="{{url('single-product')}}" class="View-btn">View</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
    
@endsection