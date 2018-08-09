<main id="main-container">
    <div class="bg-image" style="background-image: url('<?php echo Yii::app()->baseUrl; ?>/plugins/img/photos/photo3@2x.jpg.jpg');">
        <div class="bg-primary-dark-op">
            <section class="content content-full content-boxed overflow-hidden">
                <!-- Section Content -->
                <div class="push-30-t push-30 text-center">
                    <h1 class="h2 text-white push-10 visibility-hidden" data-toggle="appear" data-class="animated fadeInDown">Wallet</h1>
                </div>
                <!-- END Section Content -->
            </section>
        </div>
    </div>

    <div class="block" style="margin-bottom:0px;">
        <div class="block-header bg-gray-lighter">
            <h3 class="block-title">Wallet</h3>
        </div>
        <section class="content content-boxed">
            <div class="block-content">
                <?php
                if ($wallets){
                    ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="block" style="margin-bottom: 0px;">
                                <div class="block-header bg-gray-lighter">
                                    <h3 class="block-title">Withdraw money</h3>
                                </div>

                                <div class="row">
                                    <div class="col-sm-4">
                                        <!-- Info Alert -->
                                        <div class="alert alert-dismissable" style="margin-left:10%">
                                            <i class="si si-wallet" style="font-size: 30px;"></i>&nbsp; <h2 style="display: inline-block;" class="font-w300 push-15" id="cbalance">&euro; <?php echo $cashbalance ; ?></h2>
                                            <p>Your wallet balance</p>
                                        </div>
                                        <!-- END Info Alert -->
                                    </div>
                                    <div class="col-sm-4" style="margin-top: 40px;">
                                        <input type="text" name="withdrawamount" id="withdraw_amount" placeholder="Enter amount" class="form-control">
                                        <div id="noamount" style="display: none; color: #b11a1a; padding: 5px 0px 0px 0px;"></div>
                                        <div id="success" style="display: none; color: darkgreen; padding: 5px 0px 0px 0px;"></div>
                                    </div>
                                    <div class="col-sm-4" style="margin-top: 40px;">
                                        <button class="text-right btn btn-primary" type="submit" name="withdrawBtn" id="btnWithdraw">Withdraw</button>
                                    </div>
                                    <div class="col-sm-4">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- All Orders -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="block" style="margin-bottom: 0px;">
                                <div class="block-header bg-gray-lighter">
                                    <h3 class="block-title">Wallet Summary</h3>
                                </div>
                                <section class="content content-boxed">
                                    <div class="block-content">
                                        <table class="table table-borderless table-striped table-vcenter">
                                            <thead>
                                            <tr>
                                                <th class="text-center" width="30%">Description</th>
                                                <th width="5%">Status</th>
                                                <th width="15%" class="visible-lg text-right">Amount</th>
                                                <th width="15%"class="visible-lg text-center">Type</th>
                                                <th width="35%"class="hidden-xs text-center">Comment</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php

                                            $i = 0;
                                            foreach ($wallets as $wallet){
                                                $i++;
                                                ?>
                                                <tr>
                                                    <td class="text-left">
                                                        <p>
                                                            <?php echo $wallet['reference_desc']?><br>
                                                            <?php echo "wallet id:".$wallet['wallet_id']; ?><br>
                                                            <?php echo "Reference number:".$wallet['reference_num']; ?><br>
                                                            <?php echo "Created at : ". $wallet['created_at']; ?>
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $class='label label-danger';
                                                        if($wallet['transaction_status']==0)
                                                        {
                                                            $wallet['transaction_status'] = 'Pending';
                                                        }
                                                        else if($wallet['transaction_status']==1)
                                                        {
                                                            $wallet['transaction_status'] = 'On Hold';
                                                        }
                                                        else if($wallet['transaction_status']==2)
                                                        {
                                                            $wallet['transaction_status'] = 'Approved';
                                                            $class='label label-success';
                                                        }
                                                        else if($wallet['transaction_status']==3)
                                                        {
                                                            $wallet['transaction_status'] = 'Rejected';
                                                        }
                                                        ?>
                                                        <span class="<?php echo $class; ?>"><?php echo $wallet['transaction_status'] ?> </span>
                                                    </td>
                                                    <td class="visible-lg text-right">
                                                        <a href="javascript:void(0);"><?php echo $wallet['amount']?></a>
                                                    </td>
                                                    <td class="text-center visible-lg">
                                                        <?php
                                                        if($wallet['transaction_type'] == 0)
                                                        {
                                                            $wallet['transaction_type'] = 'Credit';
                                                        }
                                                        else if($wallet['transaction_type'] == 1)
                                                        {
                                                            $wallet['transaction_type'] = 'Debit';
                                                        }
                                                        ?>
                                                        <a href="javascript:void(0)"><?php echo $wallet['transaction_type']; ?> </a>
                                                    </td>
                                                    <td class="hidden-xs text-center">
                                                        <p><?php echo $wallet['transaction_comment'];?></p>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                    <!-- END All Orders -->

                <?php }
                else{ ?>
                    <table class="table table-borderless table-striped table-vcenter">
                        <tr>
                            <td class="text-center">

                            </td>
                            <td class="hidden-xs text-center"></td>
                            <td width="15%" class="text-right">No record found!</td>
                            <td class="text-right hidden-xs">
                                <strong></strong>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-xs">

                                </div>
                            </td>
                        </tr>
                    </table>
                <?php }?>
            </div>
        </section>
    </div>

</main>
<script>
    $("#btnWithdraw").click(function () {
        $("#noamount, #success").hide();
        $("#noamount").html('');
        $("#noamount").parent().removeClass('has-error');
        var CheckAmount = '<?php echo Yii::app()->createUrl('Wallet/CheckWithdrawAmount')?>';
        var Withdraw = '<?php echo Yii::app()->createUrl('Wallet/WithdrawFunds')?>';
        var data = { 'amount' : $('#withdraw_amount').val()}
        if ($('#withdraw_amount').val()) {
            $.ajax({
                url: CheckAmount,
                type: "post",
                data: data,
                success: function (response) {
                    var Result = JSON.parse(response);
                    if (Result.token == 1) {
                        $.ajax({
                            url: Withdraw,
                            type: "post",
                            data: data,
                            success: function (response) {
                                var Result = JSON.parse(response);
                                if (Result.token == 1) {
                                    $("#cbalance").html('€ ' + Result.cbalance);
                                    $("#success").html('Amount Successfully transfer');
                                    $("#success").show();
                                    $('#withdraw_amount').val('');
                                    window.location.reload();
                                } else {
                                    $("#noamount").html('Not enough amount in your wallet');
                                    $("#noamount").show();
                                    $("#noamount").parent().addClass('has-error');
                                }
                            }
                        });
                    } else {
                        $("#noamount").html('Not enough amount in your wallet');
                        $("#noamount").show();
                        $("#noamount").parent().addClass('has-error');
                    }

                }
            });
        }else{
            $("#noamount").html('Please enter amount');
            $("#noamount").show();
            $("#noamount").parent().addClass('has-error');
        }
    });
</script>