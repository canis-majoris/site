<div class="row">
    <div class="col s12" style="padding: 0;">
      <ul class="tabs transactions-tabs">
        <li class="tab col s6 l6 m6"><a class="active" href="#cartu_trans_1">Cartu</a></li>
        <li class="tab col s6 l6 m6"><a href="#paypal_trans_1">Paypal</a></li>
      </ul>
    </div>
    <div id="cartu_trans_1" class="tabs-content-container-inline col s12">
        <div class="Rtable Rtable--3cols-custom">
            @foreach($cartu_transactions as $c_trans)
                @php($currency_sign = App\Models\Currency::where('region_id', $c_trans->region_id)->first()->sign)
                <!-- <div class="info-item transactions-item"> -->
                    <span class="info-title-1 Rtable-cell info-item"><b>{{ $currency_sign . number_format($c_trans->m_amt/100, 2) }}</b></span>
                    <div class="Rtable-cell text-center info-item">
                        @if($c_trans->result == 'Y')
                            <i class="material-icons">check</i>
                        @elseif($c_trans->result == 'U')
                            <i class="material-icons">close</i>
                        @endif
                    </div>
                    <span class="info-time-1 Rtable-cell info-item"><small>{{ \Carbon\Carbon::parse($c_trans->date)->diffForHumans() }}</small></span>
                <!-- </div>
                <div class="inner-sidebar-divider"></div> -->
            @endforeach
        </div>
        <a href="{{route('transactions.index', 'provider=cartu')}}" target="_blank" class="btn-flat btn btn-xs waves-effect waves-teal inline-btn-custom right" style="line-height: 22px;"><i class="material-icons">list</i> показать все</a>
    </div>
    <div id="paypal_trans_1" class="tabs-content-container-inline col s12">
        <div class="Rtable Rtable--3cols-custom">
            @foreach($paypal_transactions as $p_trans)
                @php($currency_sign = App\Models\Currency::where('region_id', $p_trans->region_id)->first()->sign)
                @php($paypal_trans_data = json_decode(str_replace(array( '"{', '}"' ), array( '{', '}' ), $p_trans->after_payment_data), 1))
                <!-- <div class="info-item transactions-item"> -->
                    <span class="info-title-1 Rtable-cell info-item"><b>{{ $currency_sign . $paypal_trans_data['mc_gross'] }}</b></span>
                    <div class="Rtable-cell text-center info-item">
                        @if($p_trans->after_payment_status == 'Completed Successfully')
                            <i class="material-icons">check</i>
                        @else
                            <i class="material-icons">close</i>
                        @endif
                    </div>
                    <span class="info-time-1 Rtable-cell info-item"><small>{{ \Carbon\Carbon::parse($p_trans->created_at)->diffForHumans() }}</small></span>
                <!-- </div>
                <div class="inner-sidebar-divider"></div> -->
            @endforeach
        </div>
        <a href="{{route('transactions.index', 'provider=paypal')}}" target="_blank" class="btn-flat btn btn-xs waves-effect waves-teal inline-btn-custom right" style="line-height: 22px;"><i class="material-icons">list</i> показать все</a>
    </div>
</div>


<!-- <h2>Row oriented table</h2>
    <div class="Rtable Rtable--4cols">
        <div class="Rtable-cell"><h3>Eddard Stark</h3></div>
        <div class="Rtable-cell">Has a sword named Ice</div>
        <div class="Rtable-cell">No direwolf</div>
        <div class="Rtable-cell"><strong>Lord of Winterfell</strong></div>
        <div class="Rtable-cell"><h3>Jon Snow</h3></div>
        <div class="Rtable-cell">Has a sword named Longclaw</div>
        <div class="Rtable-cell">Direwolf: Ghost</div>
        <div class="Rtable-cell"><strong>Knows nothing</strong></div>
        <div class="Rtable-cell"><h3>Arya Stark</h3></div>
        <div class="Rtable-cell">Has a sword named Needle</div>
        <div class="Rtable-cell">Direwolf: Nymeria</div>
        <div class="Rtable-cell"><strong>No one</strong></div>
    </div>

 -->

<script type="text/javascript">
    var delay = (function(){
      var timer = 50;
      return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
      };
    })();

    $(document).ready(function(e) {
        $('ul.tabs').tabs({
            swipeable: false
        });

        delay(function(){
            $('ul.transactions-tabs .tab').first().find('a').click();
        }, 200 );


    });



</script>