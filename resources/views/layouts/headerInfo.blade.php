<div class="container-fluid mb-1 pb-2">
    <div class="row" style="border-bottom: 1px solid gray;padding-bottom: 5px;margin-left: 0;margin-right: 0;">
        <div class="col-2 ps-0">
            <img src="{{asset($company->logo ? $company->logo : 'noImage.jpg')}}" class="w-100 h-100" style="border-radius:5px;">
        </div>
        <div class="col-10 pe-0">
            <h4 class="m-0">{{$company->title}}</h4>
            <address class="m-0">{{$company->address}}</address>
        </div>
    </div>
</div>