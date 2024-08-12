@extends('User.layouts.app')


@section('content')
    <main class="main-dashboard">
        <section class="categories-list stock-reconciliation">
            <div class="recent-sales">
                <div class="input-search-box-container">
                    <h2 class="font-26">Stock Reconciliation</h2>
                    <input type="text" id="Search-bar" placeholder="Search">
                </div>
                <div class="main-table">
                    <table>
                        <tbody>
                            <tr class="heading">
                                <th>Sr. No.</th>
                                <th>Name of product</th>
                                <th>Stock Count</th>
                                <th>Order Count</th>
                            </tr>
                            <tr>
                                <td>#0038</td>
                                <td>Agaves & Artificial Cycads</td>
                                <td>225</td>
                                <td>112</td>
                            </tr>
                            <tr>
                                <td>#0033</td>
                                <td>Aquarium & Reptile Artificial Plants</td>
                                <td>2215</td>
                                <td>1245</td>
                            </tr>
                            <tr>
                                <td>#0034</td>
                                <td>Artificial Ferns</td>
                                <td>451</td>
                                <td>325</td>
                            </tr>
                            <tr>
                                <td>#0035</td>
                                <td>Artificial Fruit Trees</td>
                                <td>4511</td>
                                <td>2578</td>
                            </tr>
                            <tr>
                                <td>#0036</td>
                                <td>Artificial Palms</td>
                                <td>5846</td>
                                <td>5800</td>
                            </tr>
                            <tr>
                                <td>#0037</td>
                                <td>Artificial Plants Arrangement</td>
                                <td>4158</td>
                                <td>521</td>
                            </tr>
                            <tr>
                                <td>#0038</td>
                                <td>Cactus & Artificial Succulents</td>
                                <td>1558</td>
                                <td>45</td>
                            </tr>
                            <tr>
                                <td>#0039</td>
                                <td>Bamboo Artificial Trees</td>
                                <td>1558</td>
                                <td>971</td>
                            </tr>
                            <tr>
                                <td>#0040</td>
                                <td>Bonsai artificial trees</td>
                                <td>5411 </td>
                                <td>3158</td>
                            </tr>
                            <tr>
                                <td>#0041</td>
                                <td>Cactus & Artificial Succulents</td>
                                <td>5152</td>
                                <td>2569</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    
@endsection