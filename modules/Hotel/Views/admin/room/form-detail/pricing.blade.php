<?php $countries = \App\Countries::all(); ?>

@if(is_default_lang())
    <input type='hidden' value='{{count($row->prices)}}' id='count'>
    <div class="row justify-content-center align-items-center g-9 mb-3">
        <div class="col-md-6">
            <div class="form-group">
                <label>{{__("Price")}} <span class="text-danger">*</span></label>
                <input type="number" required value="{{$row->price}}" min="1" placeholder="{{__("Price")}}" name="price" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>{{__("Number of room")}} <span class="text-danger">*</span></label>
                <input type="number" required value="{{$row->number ?? 1}}" min="1" max="100" placeholder="{{__("Number")}}" name="number" class="form-control">
            </div>
        </div>
    </div>

    <div class="col-12" id="country_price">


        {{-- end row --}}
        @foreach($row->countryPrices as $INDEX=>$p)
            <div class="row justify-content-center align-items-end g-9 mb-3" data-select2-id="select2-data-72-jo53">
                <!--begin::Col-->
                <div class="col-md-6 fv-row" data-select2-id="select2-data-71-0h3a">
                    <label class="required fs-6 fw-bold mb-2"><strong>{{__('Countries')}}</strong></label>
                    <select name="arr[{{$INDEX}}][country][]" data-selected-text-format="count > 3" class="selectpicker" data-live-search="true" multiple>
                        @foreach($countries as $c)
                            <option  @foreach($p->ips as $ip)@if($ip->room_id == $p->room_id && $ip->ip == $c->code) selected @endif @endforeach value="{{$c->code}}">{{$c->name}}</option>
                        @endforeach
                    </select>
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-md-3 fv-row">
                    <label class="required fs-6 fw-bold mb-2">{{__('price')}}</label>
                    <!--begin::Input-->
                    <div class="position-relative d-flex align-items-center">

                        <!--begin::Datepicker-->
                        <input type="number" name="arr[{{$INDEX}}][price]" value='{{$p->price}}' class="form-control text-view" required>
                        <!--end::Datepicker-->
                    </div>
                    <!--end::Input-->

                </div>
                <!--end::Col-->

                <div class="col-md-3 fv-row">
                    <button type="button" class="btn btn-sm btn-danger remove-countries-price">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
    <div class="col-md-12" >
        <a class='btn btn-danger'  id='add'>+</a>
        {{--     <a>--}}
        {{--        <p id='show'>{{__('choose country code')}}</p>--}}
        {{--     </a>--}}
        <div style='max-height: 500px; overflow:scroll' id='more'>
            <table cellspacing="0">
                <tr>
                    <th>
                        <strong>{{__('Country')}}</strong>
                    </th>
                    <th>
                        <strong>{{__('Code')}}  {{__('Country')}}</strong>
                    </th>
                </tr>
                <tr>
                    <td valign="top">
                        <strong>A</strong>
                    </td>
                    <td valign="top">
                        &nbsp;
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        AFGHANISTAN
                    </td>
                    <td valign="top">
                        AF
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        ALAND ISLANDS
                    </td>
                    <td valign="top">
                        AX
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        ALBANIA
                    </td>
                    <td valign="top">
                        AL
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        ALGERIA
                    </td>
                    <td valign="top">
                        DZ
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        AMERICAN SAMOA
                    </td>
                    <td valign="top">
                        AS
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        ANDORRA
                    </td>
                    <td valign="top">
                        AD
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        ANGOLA
                    </td>
                    <td valign="top">
                        AO
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        ANGUILLA
                    </td>
                    <td valign="top">
                        AI
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        ANTARCTICA
                    </td>
                    <td valign="top">
                        AQ
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        ANTIGUA AND BARBUDA
                    </td>
                    <td valign="top">
                        AG
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        ARGENTINA
                    </td>
                    <td valign="top">
                        AR
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        ARMENIA
                    </td>
                    <td valign="top">
                        AM
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        ARUBA
                    </td>
                    <td valign="top">
                        AW
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        AUSTRALIA
                    </td>
                    <td valign="top">
                        AU
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        AUSTRIA
                    </td>
                    <td valign="top">
                        AT
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        AZERBAIJAN
                    </td>
                    <td valign="top">
                        AZ
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        <strong>B</strong>
                    </td>
                    <td valign="top">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        BAHAMAS
                    </td>
                    <td valign="top">
                        BS
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        BAHRAIN
                    </td>
                    <td valign="top">
                        BH
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        BANGLADESH
                    </td>
                    <td valign="top">
                        BD
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        BARBADOS
                    </td>
                    <td valign="top">
                        BB
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        BELARUS
                    </td>
                    <td valign="top">
                        BY
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        BELGIUM
                    </td>
                    <td valign="top">
                        BE
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        BELIZE
                    </td>
                    <td valign="top">
                        BZ
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        BENIN
                    </td>
                    <td valign="top">
                        BJ
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        BERMUDA
                    </td>
                    <td valign="top">
                        BM
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        BHUTAN
                    </td>
                    <td valign="top">
                        BT
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        BOLIVIA, PLURINATIONAL STATE OF
                    </td>
                    <td valign="top">
                        BO
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        BONAIRE, SAINT EUSTATIUS AND SABA
                    </td>
                    <td valign="top">
                        BQ
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        BOSNIA AND HERZEGOVINA
                    </td>
                    <td valign="top">
                        BA
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        BOTSWANA
                    </td>
                    <td valign="top">
                        BW
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        BOUVET ISLAND
                    </td>
                    <td valign="top">
                        BV
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        BRAZIL
                    </td>
                    <td valign="top">
                        BR
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        BRITISH INDIAN OCEAN TERRITORY
                    </td>
                    <td valign="top">
                        IO
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        BRUNEI DARUSSALAM
                    </td>
                    <td valign="top">
                        BN
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        BULGARIA
                    </td>
                    <td valign="top">
                        BG
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        BURKINA FASO
                    </td>
                    <td valign="top">
                        BF
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        BURUNDI
                    </td>
                    <td valign="top">
                        BI
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        <strong>C</strong>
                    </td>
                    <td valign="top">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        CAMBODIA
                    </td>
                    <td valign="top">
                        KH
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        CAMEROON
                    </td>
                    <td valign="top">
                        CM
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        CANADA
                    </td>
                    <td valign="top">
                        CA
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        CAPE VERDE
                    </td>
                    <td valign="top">
                        CV
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        CAYMAN ISLANDS
                    </td>
                    <td valign="top">
                        KY
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        CENTRAL AFRICAN REPUBLIC
                    </td>
                    <td valign="top">
                        CF
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        CHAD
                    </td>
                    <td valign="top">
                        TD
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        CHILE
                    </td>
                    <td valign="top">
                        CL
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        CHINA
                    </td>
                    <td valign="top">
                        CN
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        CHRISTMAS ISLAND
                    </td>
                    <td valign="top">
                        CX
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        COCOS (KEELING) ISLANDS
                    </td>
                    <td valign="top">
                        CC
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        COLOMBIA
                    </td>
                    <td valign="top">
                        CO
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        COMOROS
                    </td>
                    <td valign="top">
                        KM
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        CONGO
                    </td>
                    <td valign="top">
                        CG
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        CONGO, THE DEMOCRATIC REPUBLIC OF THE
                    </td>
                    <td valign="top">
                        CD
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        COOK ISLANDS
                    </td>
                    <td valign="top">
                        CK
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        COSTA RICA
                    </td>
                    <td valign="top">
                        CR
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        COTE D'IVOIRE
                    </td>
                    <td valign="top">
                        CI
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        CROATIA
                    </td>
                    <td valign="top">
                        HR
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        CUBA
                    </td>
                    <td valign="top">
                        CU
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        CURACAO
                    </td>
                    <td valign="top">
                        CW
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        CYPRUS
                    </td>
                    <td valign="top">
                        CY
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        CZECH REPUBLIC
                    </td>
                    <td valign="top">
                        CZ
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        <strong>D</strong>
                    </td>
                    <td valign="top">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        DENMARK
                    </td>
                    <td valign="top">
                        DK
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        DJIBOUTI
                    </td>
                    <td valign="top">
                        DJ
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        DOMINICA
                    </td>
                    <td valign="top">
                        DM
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        DOMINICAN REPUBLIC
                    </td>
                    <td valign="top">
                        DO
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <strong>E</strong>
                    </td>
                    <td valign="top">
                        &nbsp;
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        ECUADOR
                    </td>
                    <td valign="top">
                        EC
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        EGYPT
                    </td>
                    <td valign="top">
                        EG
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        EL SALVADOR
                    </td>
                    <td valign="top">
                        SV
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        EQUATORIAL GUINEA
                    </td>
                    <td valign="top">
                        GQ
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        ERITREA
                    </td>
                    <td valign="top">
                        ER
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        ESTONIA
                    </td>
                    <td valign="top">
                        EE
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        ETHIOPIA
                    </td>
                    <td valign="top">
                        ET
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <strong>F</strong>
                    </td>
                    <td valign="top">
                        &nbsp;
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        FALKLAND ISLANDS (MALVINAS)
                    </td>
                    <td valign="top">
                        FK
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        FAROE ISLANDS
                    </td>
                    <td valign="top">
                        FO
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        FIJI
                    </td>
                    <td valign="top">
                        FJ
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        FINLAND
                    </td>
                    <td valign="top">
                        FI
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        FRANCE
                    </td>
                    <td valign="top">
                        FR
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        FRENCH GUIANA
                    </td>
                    <td valign="top">
                        GF
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        FRENCH POLYNESIA
                    </td>
                    <td valign="top">
                        PF
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        FRENCH SOUTHERN TERRITORIES
                    </td>
                    <td valign="top">
                        TF
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        <strong>G</strong>
                    </td>
                    <td valign="top">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        GABON
                    </td>
                    <td valign="top">
                        GA
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        GAMBIA
                    </td>
                    <td valign="top">
                        GM
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        GEORGIA
                    </td>
                    <td valign="top">
                        GE
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        GERMANY
                    </td>
                    <td valign="top">
                        DE
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        GHANA
                    </td>
                    <td valign="top">
                        GH
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        GIBRALTAR
                    </td>
                    <td valign="top">
                        GI
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        GREECE
                    </td>
                    <td valign="top">
                        GR
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        GREENLAND
                    </td>
                    <td valign="top">
                        GL
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        GRENADA
                    </td>
                    <td valign="top">
                        GD
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        GUADELOUPE
                    </td>
                    <td valign="top">
                        GP
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        GUAM
                    </td>
                    <td valign="top">
                        GU
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        GUATEMALA
                    </td>
                    <td valign="top">
                        GT
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        GUERNSEY
                    </td>
                    <td valign="top">
                        GG
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        GUINEA
                    </td>
                    <td valign="top">
                        GN
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        GUINEA-BISSAU
                    </td>
                    <td valign="top">
                        GW
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        GUYANA
                    </td>
                    <td valign="top">
                        GY
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <strong>H</strong>
                    </td>
                    <td valign="top">
                        &nbsp;
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        HAITI
                    </td>
                    <td valign="top">
                        HT
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        HEARD ISLAND AND MCDONALD ISLANDS
                    </td>
                    <td valign="top">
                        HM
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        HOLY SEE (VATICAN CITY STATE)
                    </td>
                    <td valign="top">
                        VA
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        HONDURAS
                    </td>
                    <td valign="top">
                        HN
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        HONG KONG
                    </td>
                    <td valign="top">
                        HK
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        HUNGARY
                    </td>
                    <td valign="top">
                        HU
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        <strong>I</strong>
                    </td>
                    <td valign="top">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        ICELAND
                    </td>
                    <td valign="top">
                        IS
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        INDIA
                    </td>
                    <td valign="top">
                        IN
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        INDONESIA
                    </td>
                    <td valign="top">
                        ID
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        IRAN, ISLAMIC REPUBLIC OF
                    </td>
                    <td valign="top">
                        IR
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        IRAQ
                    </td>
                    <td valign="top">
                        IQ
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        IRELAND
                    </td>
                    <td valign="top">
                        IE
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        ISLE OF MAN
                    </td>
                    <td valign="top">
                        IM
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        ISRAEL
                    </td>
                    <td valign="top">
                        IL
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        ITALY
                    </td>
                    <td valign="top">
                        IT
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        <strong>J</strong>
                    </td>
                    <td valign="top">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        JAMAICA
                    </td>
                    <td valign="top">
                        JM
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        JAPAN
                    </td>
                    <td valign="top">
                        JP
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        JERSEY
                    </td>
                    <td valign="top">
                        JE
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        JORDAN
                    </td>
                    <td valign="top">
                        JO
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <strong>K</strong>
                    </td>
                    <td valign="top">
                        &nbsp;
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        KAZAKHSTAN
                    </td>
                    <td valign="top">
                        KZ
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        KENYA
                    </td>
                    <td valign="top">
                        KE
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        KIRIBATI
                    </td>
                    <td valign="top">
                        KI
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        KOREA, DEMOCRATIC PEOPLE'S REPUBLIC OF
                    </td>
                    <td valign="top">
                        KP
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        KOREA, REPUBLIC OF
                    </td>
                    <td valign="top">
                        KR
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        KUWAIT
                    </td>
                    <td valign="top">
                        KW
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        KYRGYZSTAN
                    </td>
                    <td valign="top">
                        KG
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <strong>L</strong>
                    </td>
                    <td valign="top">
                        &nbsp;
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        LAO PEOPLE'S DEMOCRATIC REPUBLIC
                    </td>
                    <td valign="top">
                        LA
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        LATVIA
                    </td>
                    <td valign="top">
                        LV
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        LEBANON
                    </td>
                    <td valign="top">
                        LB
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        LESOTHO
                    </td>
                    <td valign="top">
                        LS
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        LIBERIA
                    </td>
                    <td valign="top">
                        LR
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        LIBYAN ARAB JAMAHIRIYA
                    </td>
                    <td valign="top">
                        LY
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        LIECHTENSTEIN
                    </td>
                    <td valign="top">
                        LI
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        LITHUANIA
                    </td>
                    <td valign="top">
                        LT
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        LUXEMBOURG
                    </td>
                    <td valign="top">
                        LU
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <strong>M</strong>
                    </td>
                    <td valign="top">
                        &nbsp;
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        MACAO
                    </td>
                    <td valign="top">
                        MO
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF
                    </td>
                    <td valign="top">
                        MK
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        MADAGASCAR
                    </td>
                    <td valign="top">
                        MG
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        MALAWI
                    </td>
                    <td valign="top">
                        MW
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        MALAYSIA
                    </td>
                    <td valign="top">
                        MY
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        MALDIVES
                    </td>
                    <td valign="top">
                        MV
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        MALI
                    </td>
                    <td valign="top">
                        ML
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        MALTA
                    </td>
                    <td valign="top">
                        MT
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        MARSHALL ISLANDS
                    </td>
                    <td valign="top">
                        MH
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        MARTINIQUE
                    </td>
                    <td valign="top">
                        MQ
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        MAURITANIA
                    </td>
                    <td valign="top">
                        MR
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        MAURITIUS
                    </td>
                    <td valign="top">
                        MU
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        MAYOTTE
                    </td>
                    <td valign="top">
                        YT
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        MEXICO
                    </td>
                    <td valign="top">
                        MX
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        MICRONESIA, FEDERATED STATES OF
                    </td>
                    <td valign="top">
                        FM
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        MOLDOVA, REPUBLIC OF
                    </td>
                    <td valign="top">
                        MD
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        MONACO
                    </td>
                    <td valign="top">
                        MC
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        MONGOLIA
                    </td>
                    <td valign="top">
                        MN
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        MONTENEGRO
                    </td>
                    <td valign="top">
                        ME
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        MONTSERRAT
                    </td>
                    <td valign="top">
                        MS
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        MOROCCO
                    </td>
                    <td valign="top">
                        MA
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        MOZAMBIQUE
                    </td>
                    <td valign="top">
                        MZ
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        MYANMAR
                    </td>
                    <td valign="top">
                        MM
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <strong>N</strong>
                    </td>
                    <td valign="top">
                        &nbsp;
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        NAMIBIA
                    </td>
                    <td valign="top">
                        NA
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        NAURU
                    </td>
                    <td valign="top">
                        NR
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        NEPAL
                    </td>
                    <td valign="top">
                        NP
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        NETHERLANDS
                    </td>
                    <td valign="top">
                        NL
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        NEW CALEDONIA
                    </td>
                    <td valign="top">
                        NC
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        NEW ZEALAND
                    </td>
                    <td valign="top">
                        NZ
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        NICARAGUA
                    </td>
                    <td valign="top">
                        NI
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        NIGER
                    </td>
                    <td valign="top">
                        NE
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        NIGERIA
                    </td>
                    <td valign="top">
                        NG
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        NIUE
                    </td>
                    <td valign="top">
                        NU
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        NORFOLK ISLAND
                    </td>
                    <td valign="top">
                        NF
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        NORTHERN MARIANA ISLANDS
                    </td>
                    <td valign="top">
                        MP
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        NORWAY
                    </td>
                    <td valign="top">
                        NO
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <strong>O</strong>
                    </td>
                    <td valign="top">
                        &nbsp;
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        OMAN
                    </td>
                    <td valign="top">
                        OM
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <strong>P</strong>
                    </td>
                    <td valign="top">
                        &nbsp;
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        PAKISTAN
                    </td>
                    <td valign="top">
                        PK
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        PALAU
                    </td>
                    <td valign="top">
                        PW
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        PALESTINIAN TERRITORY, OCCUPIED
                    </td>
                    <td valign="top">
                        PS
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        PANAMA
                    </td>
                    <td valign="top">
                        PA
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        PAPUA NEW GUINEA
                    </td>
                    <td valign="top">
                        PG
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        PARAGUAY
                    </td>
                    <td valign="top">
                        PY
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        PERU
                    </td>
                    <td valign="top">
                        PE
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        PHILIPPINES
                    </td>
                    <td valign="top">
                        PH
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        PITCAIRN
                    </td>
                    <td valign="top">
                        PN
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        POLAND
                    </td>
                    <td valign="top">
                        PL
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        PORTUGAL
                    </td>
                    <td valign="top">
                        PT
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        PUERTO RICO
                    </td>
                    <td valign="top">
                        PR
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        <strong>Q</strong>
                    </td>
                    <td valign="top">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        QATAR
                    </td>
                    <td valign="top">
                        QA
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        <strong>R</strong>
                    </td>
                    <td valign="top">
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        REUNION
                    </td>
                    <td valign="top">
                        RE
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        ROMANIA
                    </td>
                    <td valign="top">
                        RO
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        RUSSIAN FEDERATION
                    </td>
                    <td valign="top">
                        RU
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        RWANDA
                    </td>
                    <td valign="top">
                        RW
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <strong>S</strong>
                    </td>
                    <td valign="top">
                        &nbsp;
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        SAINT BARTHELEMY
                    </td>
                    <td valign="top">
                        BL
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        SAINT HELENA, ASCENSION AND TRISTAN DA CUNHA
                    </td>
                    <td valign="top">
                        SH
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        SAINT KITTS AND NEVIS
                    </td>
                    <td valign="top">
                        KN
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        SAINT LUCIA
                    </td>
                    <td valign="top">
                        LC
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        SAINT MARTIN (FRENCH PART)
                    </td>
                    <td valign="top">
                        MF
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        SAINT PIERRE AND MIQUELON
                    </td>
                    <td valign="top">
                        PM
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        SAINT VINCENT AND THE GRENADINES
                    </td>
                    <td valign="top">
                        VC
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        SAMOA
                    </td>
                    <td valign="top">
                        WS
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        SAN MARINO
                    </td>
                    <td valign="top">
                        SM
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        SAO TOME AND PRINCIPE
                    </td>
                    <td valign="top">
                        ST
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        SAUDI ARABIA
                    </td>
                    <td valign="top">
                        SA
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        SENEGAL
                    </td>
                    <td valign="top">
                        SN
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        SERBIA
                    </td>
                    <td valign="top">
                        RS
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        SEYCHELLES
                    </td>
                    <td valign="top">
                        SC
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        SIERRA LEONE
                    </td>
                    <td valign="top">
                        SL
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        SINGAPORE
                    </td>
                    <td valign="top">
                        SG
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        SINT MAARTEN (DUTCH PART)
                    </td>
                    <td valign="top">
                        SX
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        SLOVAKIA
                    </td>
                    <td valign="top">
                        SK
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        SLOVENIA
                    </td>
                    <td valign="top">
                        SI
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        SOLOMON ISLANDS
                    </td>
                    <td valign="top">
                        SB
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        SOMALIA
                    </td>
                    <td valign="top">
                        SO
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        SOUTH AFRICA
                    </td>
                    <td valign="top">
                        ZA
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS
                    </td>
                    <td valign="top">
                        GS
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        SPAIN
                    </td>
                    <td valign="top">
                        ES
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        SRI LANKA
                    </td>
                    <td valign="top">
                        LK
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        SUDAN
                    </td>
                    <td valign="top">
                        SD
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        SURINAME
                    </td>
                    <td valign="top">
                        SR
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        SVALBARD AND JAN MAYEN
                    </td>
                    <td valign="top">
                        SJ
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        SWAZILAND
                    </td>
                    <td valign="top">
                        SZ
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        SWEDEN
                    </td>
                    <td valign="top">
                        SE
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        SWITZERLAND
                    </td>
                    <td valign="top">
                        CH
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        SYRIAN ARAB REPUBLIC
                    </td>
                    <td valign="top">
                        SY
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        <strong>T</strong>
                    </td>
                    <td valign="top">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        TAIWAN, PROVINCE OF CHINA
                    </td>
                    <td valign="top">
                        TW
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        TAJIKISTAN
                    </td>
                    <td valign="top">
                        TJ
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        TANZANIA, UNITED REPUBLIC OF
                    </td>
                    <td valign="top">
                        TZ
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        THAILAND
                    </td>
                    <td valign="top">
                        TH
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        TIMOR-LESTE
                    </td>
                    <td valign="top">
                        TL
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        TOGO
                    </td>
                    <td valign="top">
                        TG
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        TOKELAU
                    </td>
                    <td valign="top">
                        TK
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        TONGA
                    </td>
                    <td valign="top">
                        TO
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        TRINIDAD AND TOBAGO
                    </td>
                    <td valign="top">
                        TT
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        TUNISIA
                    </td>
                    <td valign="top">
                        TN
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        TURKEY
                    </td>
                    <td valign="top">
                        TR
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        TURKMENISTAN
                    </td>
                    <td valign="top">
                        TM
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        TURKS AND CAICOS ISLANDS
                    </td>
                    <td valign="top">
                        TC
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        TUVALU
                    </td>
                    <td valign="top">
                        TV
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <strong>U</strong>
                    </td>
                    <td valign="top">
                        &nbsp;
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        UGANDA
                    </td>
                    <td valign="top">
                        UG
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        UKRAINE
                    </td>
                    <td valign="top">
                        UA
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        UNITED ARAB EMIRATES
                    </td>
                    <td valign="top">
                        AE
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        UNITED KINGDOM
                    </td>
                    <td valign="top">
                        GB
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        UNITED STATES
                    </td>
                    <td valign="top">
                        US
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        UNITED STATES MINOR OUTLYING ISLANDS
                    </td>
                    <td valign="top">
                        UM
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        URUGUAY
                    </td>
                    <td valign="top">
                        UY
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        UZBEKISTAN
                    </td>
                    <td valign="top">
                        UZ
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        <strong>V</strong>
                    </td>
                    <td valign="top">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        VANUATU
                    </td>
                    <td valign="top">
                        VU
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        VATICAN CITY STATE
                    </td>
                    <td valign="top">
                        see <em>HOLY SEE</em>
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        VENEZUELA, BOLIVARIAN REPUBLIC OF
                    </td>
                    <td valign="top">
                        VE
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        VIET NAM
                    </td>
                    <td valign="top">
                        VN
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        VIRGIN ISLANDS, BRITISH
                    </td>
                    <td valign="top">
                        VG
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        VIRGIN ISLANDS, U.S.
                    </td>
                    <td valign="top">
                        VI
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <strong>W</strong>
                    </td>
                    <td valign="top">
                        &nbsp;
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        WALLIS AND FUTUNA
                    </td>
                    <td valign="top">
                        WF
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        WESTERN SAHARA
                    </td>
                    <td valign="top">
                        EH
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        <strong>Y</strong>
                    </td>
                    <td valign="top">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        YEMEN
                    </td>
                    <td valign="top">
                        YE
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        <strong>Z</strong>
                    </td>
                    <td valign="top">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        ZAMBIA
                    </td>
                    <td valign="top">
                        ZM
                    </td>
                </tr>
                <tr class="zebra">
                    <td valign="top">
                        ZIMBABWE
                    </td>
                    <td valign="top">
                        ZW
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <hr>
    @if(is_default_lang())
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="control-label">{{__("Min day stays")}}</label>
                    <input type="number" name="min_day_stays" class="form-control" value="{{$row->min_day_stays}}" placeholder="{{__("Ex: 2")}}">
                    <i>{{ __("Leave blank if you dont need to use the min day stays option") }}</i>
                </div>
            </div>
        </div>
        <hr>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>{{__("Number of beds")}} </label>
                <input type="number"  value="{{$row->beds ?? 1}}" min="1" max="10" placeholder="{{__("Number")}}" name="beds" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>{{__("Room Size")}} </label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="size" value="{{$row->size ?? 0}}" placeholder="{{__("Room size")}}" >
                    <div class="input-group-append">
                        <span class="input-group-text" >{!! size_unit_format() !!}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>{{__("Max Adults")}} </label>
                <input type="number" min="1"  value="{{$row->adults ?? 1}}"  name="adults" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>{{__("Max Children")}} </label>
                <input type="number" min="0"  value="{{$row->children ?? 0}}"  name="children" class="form-control">
            </div>
        </div>
    </div>
    <hr>
@endif
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    var i = $('#count').val();
    $('#more').hide();
    $('#add').click(function(){
        //    alert('here');

        // event.preventDefault();

        //    alert('here');
        var input = '<div class="row justify-content-center align-items-center g-9 mb-3"><div class="col-md-6"><div class="form-title"><strong>{{__("Countries")}}</strong></div><div class="form-group"><div class="upload-btn-wrapper"><div class="input-group">\n' +
            '<select name="arr['+i+'][country][]" data-live-search="true" data-selected-text-format="count > 3" class="selectpicker" multiple>\n' +
                @foreach($countries as $c)
                    '<option value="{{$c->code}}">{{$c->name}}</option>\n' +
                @endforeach
                    '</select>\n'+
            '</div></div></div></div><div class="col-md-3"><div class="form-title"><strong>{{__("Price")}}</strong></div><div class="form-group"><div class="upload-btn-wrapper"><div class="input-group"><input type="number" name="arr['+i+'][price]" class="form-control text-view" required></div></div></div></div>\n'+
            '<div class="col-md-3 fv-row">\n' +
            '                <button type="button" class="btn btn-sm btn-danger remove-countries-price">\n' +
            '                    <i class="fa fa-trash"></i>\n' +
            '                </button>\n' +
            '            </div></div>';
        $('#country_price').append(input);
        i++;

        $('.selectpicker').selectpicker();

        $('.remove-countries-price').click(function () {
            // $(this).parent().remove();
            console.log($(this).parent().parent('.row').remove());
            $(this).parent().remove();
        });
    });

    $('.remove-countries-price').click(function () {
        $(this).parents('.row.justify-content-center').remove();
    });


    // $('#show').click(function(){
    //  $('#more').toggle();
    // });
</script>
<style>
    .bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn){
        width: 100% !important;
    }
</style>
