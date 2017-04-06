<% extends("layouts/website") %>

<% part("content") %>

<!-- Header -->
<a name="header"></a>
<div class="intro-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="intro-message">
                    <h1>{{ $header->getTitle() }}</h1>
                    {{! $header->getBody() !}}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ügyvitel -->
<a name="ugyvitel"></a>
<div class="content-section-a">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-sm-6">
                {{! $ugyvitel->getBody() !}}
            </div>
            <div class="col-lg-5 col-lg-offset-2 col-sm-6 bordered-img-container">
                <img class="img-responsive" src="/website/img/pexels-photo-237675.jpeg" alt="Ügyvitel">
            </div>
        </div>
    </div>
</div>

<!-- Import-Export -->
<a name="import-export"></a>
<div class="content-section-b">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-lg-offset-1 col-sm-push-6  col-sm-6">
                {{! $importExport->getBody() !}}
            </div>
            <div class="col-lg-5 col-sm-pull-6 col-sm-6 bordered-img-container">
                <img class="img-responsive" src="/website/img/pexels-photo-122164.jpeg" alt="Import-Export">
            </div>
        </div>
    </div>
</div>

<!-- Tanácsadás -->
<a name="tanacsadas"></a>
<div class="content-section-a">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-sm-6">
                {{! $tanacsadas->getBody() !}}
            </div>
            <div class="col-lg-5 col-lg-offset-2 col-sm-6 bordered-img-container">
                <img class="img-responsive" src="/website/img/pexels-photo-288477.jpeg" alt="Tanácsadás">
            </div>
        </div>
    </div>
</div>

<!-- Szoftver -->
<a name="szoftver"></a>
<div class="content-section-b">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-lg-offset-1 col-sm-push-6  col-sm-6">
                {{! $szoftver->getBody() !}}
            </div>
            <div class="col-lg-5 col-sm-pull-6 col-sm-6 bordered-img-container">
                <img class="img-responsive" src="/website/img/pexels-photo-173983.jpeg" alt="Szoftver">
            </div>
        </div>
    </div>
</div>

<!-- Kapcsolat -->
<a name="kapcsolat"></a>
<div class="content-section-a">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-sm-6">
                {{! $kapcsolat->getBody() !}}
            </div>
            <div class="col-lg-7 col-lg-offset-2 col-sm-6 map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2695.5043252294363!2d19.037624316274556!3d47.49956880335754!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4741dc3d0dbf9fd1%3A0x5b446ebcd0645d53!2sBudapest%2C+F%C5%91+u.+8%2C+1011+Hungary!5e0!3m2!1sen!2sde!4v1491256323930" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
            </div>
        </div>
    </div>

</div>
<!-- /.content-section-a -->

<% endpart %>
