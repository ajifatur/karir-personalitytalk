@extends('layouts/admin/main')

@section('title', 'Data Hasil Tes: '.$user->nama_user)

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-2 mb-sm-0">Data Hasil Tes</h1>
    <a href="#" class="btn btn-sm btn-primary btn-print"><i class="bi-printer me-1"></i> Cetak</a>
</div>
<div class="row">
    <div class="col-md-4 col-xl-3">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Profil</h5></div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Nama:</span>
                        <span>{{ $user->nama_user }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Usia:</span>
                        <span>{{ generate_age($user->tanggal_lahir, $result->created_at).' tahun' }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Jenis Kelamin:</span>
                        <span>{{ gender($user->jenis_kelamin) }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Jabatan:</span>
                        <span>{{ !empty($user_desc) ? $user_desc->nama_posisi : $role->nama_role }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Role:</span>
                        <span>{{ role($user->role) }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Tes:</span>
                        <span>{{ $result->nama_tes }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-8 col-xl-9">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Hasil Tes</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-auto mx-auto">
                        <table class="table table-bordered mb-4">
                            <thead>
                                <tr>
                                    <th width="100" class="bg-info text-light">Biru</th>
                                    <th width="100" class="bg-danger text-light">Merah</th>
                                    <th width="100" class="bg-success text-light">Hijau</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $result->hasil["A"] }}</td>
                                    <td>{{ $result->hasil["B"] }}</td>
                                    <td>{{ $result->hasil["C"] }}</td>
                                </tr>
                                <tr>
                                    <td>{{ $result->hasil["D"] }}</td>
                                    <td>{{ $result->hasil["E"] }}</td>
                                    <td>{{ $result->hasil["F"] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-auto mx-auto">
                        <div class="table-responsive">
                        <div id="json" style="display:none;">
                        <?php
                            $coords = array(
                                "biru" => array($result->hasil["A"], $result->hasil["D"]),
                                "merah" => array($result->hasil["B"], $result->hasil["E"]),
                                "hijau" => array($result->hasil["C"], $result->hasil["F"]),
                            );
                        
                            echo json_encode($coords);
                        ?>
                        </div>
                        <img id="scream" src="{{ asset('assets/images/tes/sdi.png') }}" style="display: none;">
                        <canvas id="myCanvas" width="608" height="545" style="border:1px solid #bebebe;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="form-print" class="d-none" method="post" action="/admin/hasil/print" target="_blank">
    @csrf
    <input type="hidden" name="nama" value="{{ $user->nama_user }}">
    <input type="hidden" name="usia" value="{{ generate_age($user->tanggal_lahir, $result->created_at).' tahun' }}">
    <input type="hidden" name="jenis_kelamin" value="{{ gender($user->jenis_kelamin) }}">
    <input type="hidden" name="posisi" value="{{ !empty($user_desc) ? $user_desc->nama_posisi.' ('.role($user->role).')' : role($user->role) }}">
    <input type="hidden" name="tes" value="{{ $result->nama_tes }}">
    <input type="hidden" name="path" value="{{ $result->path }}">
    <input type="hidden" name="image" id="image">
</form>

@endsection

@section('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>
<script type="text/javascript">

    var canvas;
    var m1, b1, m2, b2, x, y;
    var biru = [];
    var merah = [];

    window.onload = function() {
        canvas = document.getElementById("myCanvas");
        ctx = canvas.getContext("2d");

        // Get image from <img>
        var img = document.getElementById("scream");

        // Render image to canvas
        ctx.drawImage(img,10,10);

        // Set line width and set coordinates
        ctx.lineWidth = 2;
        var coords = {
            "biru": {
                "0": [[312,520],[576,64]],"1": [[310,517],[572,64]],"2": [[308,512],[566,64]],"3": [[305,508],[560,64]],"4": [[302,503],[556,64]],"5": [[300,498],[551,64]],"6": [[296,492],[547,64]],"7": [[293,488],[542,64]],"8": [[291,484],[534,64]],"9": [[289,480],[529,64]],
                "10": [[286,476],[524,64]],"11": [[284,471],[519,64]],"12": [[281,465],[514,64]],"13": [[278,461],[508,64]],"14": [[275,456],[503,64]],"15": [[273,452],[498,64]],"16": [[271,448],[492,64]],"17": [[268,444],[486,64]],"18": [[265,439],[481,64]],"19": [[263,434],[476,64]],
                "20": [[260,430],[471,64]],"21": [[257,425],[465,64]],"22": [[254,419],[462,64]],"23": [[251,415],[456,64]],"24": [[249,411],[450,64]],"25": [[247,406],[445,64]],"26": [[244,403],[440,64]],"27": [[241,398],[434,64]],"28": [[239,393],[429,64]],"29": [[236,388],[423,64]],
                "30": [[233,383],[417,64]],"31": [[230,380],[412,64]],"32": [[231,379],[413,64]],"33": [[228,375],[408,64]],"34": [[223,365],[397,64]],"35": [[220,361],[392,64]],"36": [[218,357],[387,64]],"37": [[215,352],[381,64]],"38": [[212,347],[376,64]],"39": [[209,343],[370,64]],
                "40": [[207,337],[366,64]],"41": [[204,333],[360,64]],"42": [[202,329],[355,64]],"43": [[199,325],[349,64]],"44": [[196,320],[343,64]],"45": [[194,316],[338,64]],"46": [[191,310],[334,64]],"47": [[188,306],[329,64]],"48": [[186,301],[324,64]],"49": [[183,297],[319,64]],
                "50": [[181,292],[313,64]],"51": [[178,288],[307,64]],"52": [[176,283],[302,64]],"53": [[173,280],[296,64]],"54": [[171,275],[291,64]],"55": [[168,269],[286,64]],"56": [[165,265],[281,64]],"57": [[163,261],[274,64]],"58": [[160,257],[269,64]],"59": [[157,251],[266,64]],
                "60": [[154,246],[260,64]],"61": [[152,242],[254,64]],"62": [[149,238],[248,64]],"63": [[146,233],[243,64]],"64": [[144,228],[238,64]],"65": [[141,223],[233,64]],"66": [[138,219],[227,64]],"67": [[136,214],[223,64]],"68": [[133,210],[218,64]],"69": [[131,205],[212,64]],
                "70": [[128,201],[207,64]],"71": [[125,195],[201,64]],"72": [[122,191],[196,64]],"73": [[120,186],[190,64]],"74": [[117,182],[185,64]],"75": [[115,178],[181,64]],"76": [[112,174],[174,64]],"77": [[110,169],[169,64]],"78": [[107,165],[164,64]],"79": [[105,160],[159,64]],
                "80": [[102,156],[153,64]],"81": [[99,151],[148,64]],"82": [[96,146],[143,64]],"83": [[94,141],[138,64]],"84": [[91,137],[132,64]],"85": [[89,132],[127,64]],"86": [[86,128],[122,64]],"87": [[83,123.5],[117,64]],"88": [[80.5,118 ],[111,64]],"89": [[78,114],[106,64]],
                "90": [[75,110],[101,64]],"91": [[72,105],[95,64]],"92": [[70,100],[90,64]],"93": [[67,96],[85,64]],"94": [[65,91],[80,64]],"95": [[62,87],[75,64]],"96": [[59.5,82],[70,64]],"97": [[57,77],[64.5,64]],"98": [[54,73],[59,64]],"99": [[52,68],[54,64]],
                "100": [[50,65],[51,63]]
            },
            "merah": {
                "0": [[312,520],[49,64]],"1": [[315,517],[54,64]],"2": [[318,512],[59,64]],"3": [[321,508],[64,64]],"4": [[323,502],[69,64]],"5": [[324,497],[74,64]],"6": [[328,493],[79,64]],"7": [[331,488],[85,64]],"8": [[333,485],[91,64]],"9": [[336,480],[97,64]],
                "10": [[339,476],[102,64]],"11": [[342,472],[107,64]],"12": [[344,466],[112,64]],"13": [[347,461],[117,64]],"14": [[350,456],[122,64]],"15": [[353,452],[127,64]],"16": [[354,448],[134,64]],"17": [[357,444],[139,64]],"18": [[360,439],[144,64]],"19": [[363,434],[148,64]],
                "20": [[366,429],[153,64]],"21": [[368,424],[158,64]],"22": [[370,421],[165,64]],"23": [[373,416],[170,64]],"24": [[375,412],[176,64]],"25": [[378,408],[182,64]],"26": [[381,402],[186,64]],"27": [[384,398],[192,64]],"28": [[386,395],[197,64]],"29": [[389,389],[203,64]],
                "30": [[391,384],[207,64]],"31": [[394,380],[212,64]],"32": [[396,375],[219,64]],"33": [[399,371],[225,64]],"34": [[402,366],[228,64]],"35": [[405,361],[234,64]],"36": [[407,356],[239,64]],"37": [[410,351],[245,64]],"38": [[413,347],[249,64]],"39": [[416,342],[255,64]],
                "40": [[418,338],[261,64]],"41": [[421,334],[266,64]],"42": [[423,329],[271,64]],"43": [[426,325],[276,64]],"44": [[429,319],[281,64]],"45": [[431,314],[286,64]],"46": [[433,311],[291,64]],"47": [[436,307],[297,64]],"48": [[439,301],[302,64]],"49": [[441,297],[307,64]],
                "50": [[444,293],[312,64]],"51": [[447,288],[318,64]],"52": [[449,283],[323,64]],"53": [[452,279],[329,64]],"54": [[455,275],[333,64]],"55": [[458,270],[339,64]],"56": [[460,265],[344,64]],"57": [[463,261],[350,64]],"58": [[466,255],[354,64]],"59": [[468,251],[359,64]],
                "60": [[470,246],[366,64]],"61": [[473,242],[371,64]],"62": [[476,237],[376,64]],"63": [[479,233],[382,64]],"64": [[481,228],[387,64]],"65": [[484,224],[392,64]],"66": [[486,219],[397,64]],"67": [[489,214],[403,64]],"68": [[492,210],[408,64]],"69": [[495,205.5],[413,64]],
                "70": [[497,201],[418,64]],"71": [[500,196],[424,64]],"72": [[502,191],[429,64]],"73": [[505,187],[434,64]],"74": [[508,182],[439,64]],"75": [[511,178],[444,64]],"76": [[513,173.5],[450,64]],"77": [[515.5,169],[455,64]],"78": [[518,164],[460,64]],"79": [[521,160],[465,64]],
                "80": [[523,154],[471,64]],"81": [[526,150.5],[476,64]],"82": [[529,145],[482,64]],"83": [[531.5,141],[487,64]],"84": [[534,136],[492,64]],"85": [[536,131],[497,64]],"86": [[539,127],[503,64]],"87": [[542,123],[508.5,64]],"88": [[544.5,118.5],[513.5,64]],"89": [[547,113.6],[519,64]],
                "90": [[549,108],[524,64]],"91": [[552,104],[529.5,64]],"92": [[555.5,100],[535,64]],"93": [[558,95.5],[540,64]],"94": [[560.5,91],[545,64]],"95": [[563,87],[550,64]],"96": [[565.5,82],[555,64]],"97": [[568,77],[560.5,64]],"98": [[571,73],[566,64]],"99": [[573,68],[571,64]],
                "100": [[575,66],[574,64]]           
            },
            "hijau": {
                "0": [[49.5,64],[576,64]],"1": [[51,68],[574,68]],"2": [[54,73],[570.5,73]],"3": [[56,77],[568.5,77]],"4": [[59.5,82],[566,82]],"5": [[61.5,86],[563.5,86]],"6": [[65,91],[561,91]],"7": [[67,96],[558,96]],"8": [[70,100],[555,100]],"9": [[72,105],[553,105]],
                "10": [[75,109],[550,109]],"11": [[78,114],[547,114]],"12": [[80,118],[545,118]],"13": [[82,123],[542,123]],"14": [[86,128],[538.5,128]],"15": [[88.5,132],[536.5,132]],"16": [[91,137],[534.5,137]],"17": [[93,141],[532,141]],"18": [[96,146],[529,146]],"19": [[98.5,150],[526.5,150]],
                "20": [[101,155],[524,155]],"21": [[104,160],[521,160]],"22": [[106,164],[519,164]],"23": [[109,169],[516,169]],"24": [[112,173],[513,173]],"25": [[114,178],[511,178]],"26": [[117,182],[508,182]],"27": [[120,188],[507,186]],"28": [[123,192],[503,192]],"29": [[125,196],[500,196]],
                "30": [[127,200],[497,200]],"31": [[130,205 ],[495,205]],"32": [[133,209],[493,209]],"33": [[135,214],[490,214]],"34": [[137,218],[487.5,218]],"35": [[140,223],[485,223]],"36": [[143,228],[481,228]],"37": [[146,232],[479,232]],"38": [[148,237],[477,237]],"39": [[151,242],[475,242]],
                "40": [[154,246],[472,246]],"41": [[156.5,251],[469,251]],"42": [[159,256],[467,256]],"43": [[162,260],[464,260]],"44": [[164,265],[460,265]],"45": [[167,270],[458,270]],"46": [[169,274],[456,274]],"47": [[172,279],[453,279]],"48": [[175,283],[451,283]],"49": [[178,288],[448,288]],
                "50": [[180,292],[445,292]],"51": [[183,297],[442.5,297]],"52": [[186,302],[439,302]],"53": [[188,306],[437,306]],"54": [[191,311],[434,311]],"55": [[193,315],[431.5,315]],"56": [[197,320],[428,320]],"57": [[199,325],[426,325]],"58": [[201.5,329],[424,329]],"59": [[204,334],[421,334]],
                "60": [[207,338],[418.5,338]],"61": [[209,343],[416,343]],"62": [[212.5,347],[413,347]],"63": [[215,352],[410.5,352]],"64": [[217,357],[408,357]],"65": [[219.5,361],[405.5,361]],"66": [[223,366],[402,366]],"67": [[225,370],[400,370]],"68": [[228,375],[397,375]],"69": [[230,379],[395,379]],
                "70": [[233,384],[392,384]],"71": [[236,389],[389,389]],"72": [[238.5,393],[386.5,393]],"73": [[241,398],[384,398]],"74": [[244,402],[381,402]],"75": [[246,407],[379,407]],"76": [[249,411],[376,411]],"77": [[252,416],[373,416]],"78": [[254.5,420],[370.5,420]],"79": [[257,425],[368.5,425]],
                "80": [[260,430],[365,430]],"81": [[262,434],[363,434]],"82": [[265,439],[360,439]],"83": [[268,443],[357,443]],"84": [[271,448],[354,448]],"85": [[273,452],[352,452]],"86": [[276,458],[349,458]],"87": [[278,462],[347,462]],"88": [[281,466],[344,466]],"89": [[283,471],[342,471]],
                "90": [[286,476],[339,476]],"91": [[289,480],[336,480]],"92": [[292,485],[333,485]],"93": [[294,489],[331,489]],"94": [[297,493],[328,493]],"95": [[300,498],[326,498]],"96": [[324,502],[302,502]],"97": [[321,507],[304,507]], "98": [[318,512],[307,512]],"99": [[316,516],[309,516]],
                "100": [[314,520],[311,520]]
            }
        };

        var json = JSON.parse($("#json").text());
        for(i = 0; i < json.biru.length; i++){
            ctx.strokeStyle = "blue";
            ctx.beginPath();
            ctx.moveTo(coords.biru[json.biru[i]][0][0], coords.biru[json.biru[i]][0][1]);
            ctx.lineTo(coords.biru[json.biru[i]][1][0], coords.biru[json.biru[i]][1][1]);
            ctx.stroke();
            
            for(var j in coords.biru[json.biru[i]]){
                biru.push([coords.biru[json.biru[i]][j][0], coords.biru[json.biru[i]][j][1]]);
            }
        }
        
        for(i = 0; i < json.hijau.length; i++){
            ctx.strokeStyle = "green";
            ctx.beginPath();
            ctx.moveTo(coords.hijau[json.hijau[i]][0][0], coords.hijau[json.hijau[i]][0][1]);
            ctx.lineTo(coords.hijau[json.hijau[i]][1][0], coords.hijau[json.hijau[i]][1][1]);
            ctx.stroke();
        }
        
        for(i = 0; i < json.merah.length; i++){
            ctx.strokeStyle = "red";
            ctx.beginPath();
            ctx.moveTo(coords.merah[json.merah[i]][0][0], coords.merah[json.merah[i]][0][1]);
            ctx.lineTo(coords.merah[json.merah[i]][1][0], coords.merah[json.merah[i]][1][1]);
            ctx.stroke();
            
            for(var j in coords.merah[json.merah[i]]){
                merah.push([coords.merah[json.merah[i]][j][0], coords.merah[json.merah[i]][j][1]]);
            }
        }
        
        var intersection1 = intersection(biru[0][0], biru[0][1], biru[1][0], biru[1][1], merah[0][0], merah[0][1], merah[1][0], merah[1][1]);
        var intersection2 = intersection(biru[2][0], biru[2][1], biru[3][0], biru[3][1], merah[2][0], merah[2][1], merah[3][0], merah[3][1], false);
        
        drawLineArrow(intersection1[0], intersection1[1], intersection2[0], intersection2[1]);
        
        generateImage();

        // Report the mouse position on click
        canvas.addEventListener("click", function (evt) {
            var mousePos = getMousePos(canvas, evt);
            alert(mousePos.x + ',' + mousePos.y);
        }, false);

        // Get Mouse Position
        function getMousePos(canvas, evt) {
            var rect = canvas.getBoundingClientRect();
            return {
                x: evt.clientX - rect.left,
                y: evt.clientY - rect.top
            };
        }
    }

    // Generate image to base 64 code
    function generateImage(){
        var url = canvas.toDataURL();
        document.getElementById("image").value = url;
    }

    // Gradient
    function gradient(a, b, c, d){
        // m = (y2 - y1) / (x2 - x1)
        var gradient = (d - b) / (c - a);
        gradient = parseFloat(gradient).toFixed(2);
        m = gradient;
        return gradient;
    }

    // Cons
    function cons(a, b, m){
        // c = y - mx
        var c = b - m * a;
        c = parseFloat(c).toFixed(2);
        return c;
    }

    // Invers number
    function invers(c){
        // invers
        return -1 * c;
    }

    // X
    function xo(m1, b1, m2, b2){
        // substitution
        var a = invers(m1) - invers(m2);
        var b = b1 - b2;
        var x = b / a;
        x = parseFloat(x).toFixed(2);
        return x;
    }

    // Y
    function yo(a, b, x){
        // substitution
        var y = parseFloat(a * x) + parseFloat(b);
        y = parseFloat(y).toFixed(2);
        return y;
    }

    // Intersection
    function intersection(x1, y1, x2, y2, x3, y3, x4, y4, arc = true){
        var m1 = gradient(x1, y1, x2, y2);
        var b1 = cons(x1, y1, m1);
        var m2 = gradient(x3, y3, x4, y4);
        var b2 = cons(x3, y3, m2);
        
        var x = xo(m1, b1, m2, b2);
        var y = yo(m1, b1, x);
        
        ctx.strokeStyle = "black";
        if(arc == true){
            ctx.beginPath();
            ctx.arc(x, y, 5, 0, 2*Math.PI);
            ctx.fill();
        }
        
        return [x, y];
    }

    // Arrow by Lavi Perchik (CodePen)
    var arrow = [
        [ 2, 0 ],
        [ -10, -5 ],
        [ -10, 5]
    ];

    function drawFilledPolygon(shape) {
        ctx.strokeStyle = "black";
        ctx.lineWidth = 2.5;
        ctx.beginPath();
        ctx.moveTo(shape[0][0],shape[0][1]);

        for(p in shape){
            if (p > 0) ctx.lineTo(shape[p][0],shape[p][1]);
        }

        ctx.lineTo(shape[0][0],shape[0][1]);
        ctx.fill();
    };

    function translateShape(shape,x,y) {
        var rv = [];
        for(p in shape)
            rv.push([parseFloat(shape[p][0]) + parseFloat(x), parseFloat(shape[p][1]) + parseFloat(y)]);
        return rv;
    };

    function rotateShape(shape,ang)
    {
        var rv = [];
        for(p in shape)
            rv.push(rotatePoint(ang,shape[p][0],shape[p][1]));
        return rv;
    };

    function rotatePoint(ang,x,y) {
        return [
            (x * Math.cos(ang)) - (y * Math.sin(ang)),
            (x * Math.sin(ang)) + (y * Math.cos(ang))
        ];
    };

    function drawLineArrow(x1,y1,x2,y2) {
        ctx.lineWidth = 2.5;
        ctx.beginPath();
        ctx.moveTo(x1,y1);
        ctx.lineTo(x2,y2);
        ctx.stroke();
        var ang = Math.atan2(y2-y1,x2-x1);
        drawFilledPolygon(translateShape(rotateShape(arrow,ang),x2,y2));
    };

    $(document).on("click", ".btn-print", function(e) {
        e.preventDefault();
        $("#form-print").submit();
    });
</script>

@endsection

@section('css')

<style type="text/css">
    table tr th, table tr td {padding: .25rem .5rem; text-align: center;}
</style>

@endsection