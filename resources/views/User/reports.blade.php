@extends('User.layouts.app')


@section('content')

  <main class="main-dashboard">
  <section class="categories-list product-list report-overview">

    <div class="recent-sales">

      <div class="input-search-box-container">
        <h2 class="font-26">Reports Overview</h2>
      </div>

      <div class="submit_section">
        <div class="date">
          <form class="search-form-box">
            <input type="date" id="calender" name="calender" placeholder="select date">
            <input type="date" id="calender" name="calender">
            <button class="main-btn search-btn">Search</button>
          </form>
        </div>
        <div class="date">
          <button class="main-btn">Download PDF</button>
        </div>
      </div>

      <section>

     
          <div class="table-tabing">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#home" role="tab">stocktake report</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#profile" role="tab">stock reconciliation report</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#messages" role="tab">inventory report</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#settings" role="tab">sales report</a>
              </li>
            </ul>
      </div>
            <!-- Tab panes -->
            <div class="tab-content">
              <div class="tab-pane active" id="home" role="tabpanel">
                <div class="main-table">
                  <table>
                    <tbody>
                      <tr class="heading">
                        <th>Sr. No.</th>
                        <th>Name</th>
                        <th>Email Address</th>
                        <th>Date</th>
                      </tr>
                      <tr>
                        <td>#0032</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0033</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0034</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0035</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0036</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0037</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0038</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0039</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0040</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0041</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane" id="profile" role="tabpanel">
                <div class="main-table">
                  <table>
                    <tbody>
                      <tr class="heading">
                        <th>Sr. No.</th>
                        <th>Name</th>
                        <th>Email Address</th>
                        <th>Date</th>
                      </tr>
                      <tr>
                        <td>#0032</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0033</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0034</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0035</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0036</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0037</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0038</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0039</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0040</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0041</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane" id="messages" role="tabpanel">
                <div class="main-table">
                  <table>
                    <tbody>
                      <tr class="heading">
                        <th>Sr. No.</th>
                        <th>Name</th>
                        <th>Email Address</th>
                        <th>Date</th>
                      </tr>
                      <tr>
                        <td>#0032</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0033</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0034</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0035</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0036</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0037</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0038</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0039</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0040</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0041</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane" id="settings" role="tabpanel">
                <div class="main-table">
                  <table>
                    <tbody>
                      <tr class="heading">
                        <th>Sr. No.</th>
                        <th>Name</th>
                        <th>Email Address</th>
                        <th>Date</th>
                      </tr>
                      <tr>
                        <td>#0032</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0033</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0034</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0035</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0036</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0037</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0038</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0039</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0040</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                      <tr>
                        <td>#0041</td>
                        <td>John Deo</td>
                        <td>johndeo@ashmail.com </td>
                        <td>August 14, 2023</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>


  
      </section>


    </div>
     </div>
  </section>
</main>

@endsection