<?php
/**
 * Created by PhpStorm.
 * User: kushal
 * Date: 24/7/18
 * Time: 7:53 PM
 */
$this->pageTitle = "Reserveer";
?>
<link rel="stylesheet" href="<?php echo Yii::app()->baseUrl."/plugins/js/vendor/swiper/css/swiper.min.css"; ?>" >
<section class="ImageBackground ImageBackground--overlay " data-overlay="5">
    <div class="ImageBackground__holder">
        <div id="carousel" class="carousel slide carousel-fade" data-ride="carousel">
            <div class="carousel-inner" role="listbox">
                <div class="item active bg bg1"></div>
                <div class="item bg bg2"></div>
                <div class="item bg bg3"></div>
                <div class="item bg bg4"></div>
                <div class="item bg bg5"></div>
                <div class="item bg bg6"></div>
                <div class="item bg bg7"></div>
                <div class="item bg bg8"></div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-md-12 event-info">
                <div class="banner-text u-MarginBottom100">
                    <div class="banner-text-inner">
                        <h1 class="u-MarginTop150 u-xs-MarginTop100 u-MarginBottom30 u-Weight700 text-uppercase wow fadeInUp" data-wow-delay="200ms">UW IDENTITEIT</h1>
                        <div class="Split Split--height2 u-MarginBottom60 wow fadeInUp" data-wow-delay="200ms"></div>
                        <div class="row u-MarginBottom30 wow fadeInUp" data-wow-delay="200ms">
                            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                                <div class="check-in-out"> <i class="fa fa-plane"></i> Om uw vlucht correct te boeken, hebben we een duidelijke copy van de voorkant van uw identiteitskaart nodig. </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                            <div class="reserveer-form text-gray u-Padding30 wow fadeInUp" data-wow-delay="200ms">
                                <h2 class="text-uppercase u-MarginTop0">Voorkant ID-kaart</h2>
                                <form name="reserveer" method="POST" action="<?php echo Yii::app()->createUrl("event/step3"); ?>" enctype="multipart/form-data">
                                    <div class="row text-center">
                                        <div class="col-sm-12">
                                            <div class="form-group u-MarginBottom10">
                                                <div class="file-upload">
                                                    <label for="upload" class="file-upload__label" id="labelforimage">
                                                        <i class="fa fa-file"></i>
                                                        <h3 class="text-uppercase u-MarginBottom5"><strong>Sleep bestanden</strong></h3>
                                                        <h5 class="text-uppercase u-MarginTop0 u-MarginBottom0">(of click om te uploaden)</h5>
                                                    </label>
                                                    <div class="image-preview-box-update"  id="imgPreviewBox" style="display:none" >
                                                        <img src="" class="image-preview" id="imagePreview" data-holder-rendered="true" style="width: 400px;" >
                                                        <a style="color: #ef963f;cursor:pointer;margin-right:45px;" class="pull-right"><label style="font-weight : 400 !important;cursor: pointer" for="upload">Change image</label></a>
                                                    </div>

                                                    <input id="upload" class="file-upload__input" type="file" name="file-upload" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 text-sm u-MarginBottom30"> </div>
                                        <div class="col-sm-12 text-center">
                                            <button type="submit" class="btn btn-primary">Verder</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>


<script type="text/javascript" src="<?php echo Yii::app()->baseUrl."/plugins/js/vendor/swiper/js/swiper.min.js"; ?>"></script>

<script type="text/javascript">
    $("#upload").on("change",function(){
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) {
            $("#imgPreviewBox").css("display","none");
            return;
        } // no file selected, or no FileReader support
        $("#imgPreviewBox").css("display","none");
        if (/^image/.test( files[0].type)){ // only image file
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file

            reader.onloadend = function(e){ // set image data
                $("#imagePreview").attr('src', e.target.result);
                $("#imgPreviewBox").css("display","block");
                $("#labelforimage").addClass("hide");
            }
        }
        else{
            $("#labelforimage").removeClass("hide");
            toastr.error("File is not image");
            return false;
        }
    });
</script>