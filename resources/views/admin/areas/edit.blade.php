<div class="form-group">
    <label for="companyName">Governorate</label>
    {!! Form::select('parent_id',$parentAreas,null,['class'=>'form-control']) !!}
</div>

<div class="form-group">
    <label for="companyName">Name in English</label>
    {!! Form::text('name_en',null,['class'=>'form-control','placeholder'=>'Name in English']) !!}
</div>

<div class="form-group">
    <label for="companyName">Name in Arabic</label>
    {!! Form::text('name_ar',null,['class'=>'form-control','placeholder'=>'Name in Arabic']) !!}
</div>

<div class="form-group">
    <label for="companyName">Active</label>
    {!! Form::checkbox('active',1,null,['class'=>'form-control']) !!}
</div>


<div class="form-group">
    <button type="submit" class="btn btn-success" style="width: 100%">Save</button>
</div>

{{--<optgroup label="Kuwait City">--}}
{{--<option value="1">Abdullah al-Salem</option>--}}
{{--<option value="2">Adailiya</option>--}}
{{--<option value="3">Bneid Al Qar</option>--}}
{{--<option value="4">Daiya</option>--}}
{{--<option value="5">Dasma</option>--}}
{{--<option value="6">Dasman</option>--}}
{{--<option value="8">Faiha</option>--}}
{{--<option value="9">Granada</option>--}}
{{--<option value="10">Jaber Al Ahmad</option>--}}
{{--<option value="19">Jibla</option>--}}
{{--<option value="11">Kaifan</option>--}}
{{--<option value="12">Khaldiya</option>--}}
{{--<option value="82">Kuwait City</option>--}}
{{--<option value="104">Kuwait Free Trade Zone</option>--}}
{{--<option value="14">Mansouriya</option>--}}
{{--<option value="15">Mirqab</option>--}}
{{--<option value="16">Mubarekiya Camps and Collages</option>--}}
{{--<option value="122">Nahdha</option>--}}
{{--<option value="123">North West Al-Sulaibikhat</option>--}}
{{--<option value="17">Nuzha</option>--}}
{{--<option value="18">Qadsiya</option>--}}
{{--<option value="110">Qairawan</option>--}}
{{--<option value="20">Qortuba</option>--}}
{{--<option value="21">Rawda</option>--}}
{{--<option value="22">Salhiya</option>--}}
{{--<option value="23">Shamiya</option>--}}
{{--<option value="24">Sharq</option>--}}
{{--<option value="100">Shuwaikh Administrative</option>--}}
{{--<option value="84">Shuwaikh Industrial</option>--}}
{{--<option value="101">Shuwaikh Industrial 1</option>--}}
{{--<option value="120">Shuwaikh Industrial 2</option>--}}
{{--<option value="121">Shuwaikh Industrial 3</option>--}}
{{--<option value="25">Shuwaikh Residential</option>--}}
{{--<option value="26">Sulaibikhat</option>--}}
{{--<option value="27">Surra</option>--}}
{{--<option value="28">Yarmouk</option>--}}
{{--</optgroup>--}}


{{--<optgroup label="Farwaniya">--}}
{{--<option value="60">Abbasiya</option>--}}
{{--<option value="61">Abdullah Al Mubarak Al Sabah</option>--}}
{{--<option value="62">Abraq Khaitan</option>--}}
{{--<option value="63">Airport</option>--}}
{{--<option value="69">Al-Dajeej</option>--}}
{{--<option value="119">Al-Shadadiya</option>--}}
{{--<option value="64">Andalous</option>--}}
{{--<option value="65">Ardiya</option>--}}
{{--<option value="66">Ardiya Small Industrial</option>--}}
{{--<option value="67">Ardiya Storage Zone</option>--}}
{{--<option value="7">Doha</option>--}}
{{--<option value="71">Farwaniya</option>--}}
{{--<option value="72">Firdous</option>--}}
{{--<option value="79">Ishbiliya</option>--}}
{{--<option value="73">Jeleeb Al-Shuyoukh</option>--}}
{{--<option value="74">Khaitan</option>--}}
{{--<option value="136">Kuwait International Airport</option>--}}
{{--<option value="75">Omariya</option>--}}
{{--<option value="76">Rabia</option>--}}
{{--<option value="77">Rai</option>--}}
{{--<option value="83">Rehab</option>--}}
{{--<option value="102">Rigai</option>--}}
{{--<option value="103">Sabah Al Nasser</option>--}}
{{--<option value="137">Sheikh Saad Aviation Terminal</option>--}}
{{--<option value="70">South Khaitan - Exhibits</option>--}}
{{--</optgroup>--}}

{{--<optgroup label="Mubarak Al Kabir">--}}
{{--<option value="46">Abu Al Hasaniya</option>--}}
{{--<option value="45">Abu Fatira</option>--}}
{{--<option value="47">Adan</option>--}}
{{--<option value="48">Al-Masayel</option>--}}
{{--<option value="51">Coast Strip B</option>--}}
{{--<option value="52">Fnaitees</option>--}}
{{--<option value="53">Messila</option>--}}
{{--<option value="54">Mubarak Al Kabeer</option>--}}
{{--<option value="49">Qurain</option>--}}
{{--<option value="50">Qusor</option>--}}
{{--<option value="81">Sabah Al Salem</option>--}}
{{--<option value="56">Sabhan Industrial Area</option>--}}
{{--<option value="57">South Wista</option>--}}
{{--<option value="58">West Abu Fatira Small Industrial</option>--}}
{{--<option value="59">Wista</option>--}}
{{--</optgroup>--}}

{{--<optgroup label="Hawally">--}}
{{--<option value="85">Al-Bidea</option>--}}
{{--<option value="43">Al-Siddeeq</option>--}}
{{--<option value="118">Anjafa</option>--}}
{{--<option value="30">Bayan</option>--}}
{{--<option value="78">Hateen</option>--}}
{{--<option value="31">Hawally</option>--}}
{{--<option value="33">Jabriya</option>--}}
{{--<option value="34">Maidan Hawally</option>--}}
{{--<option value="35">Mishref</option>--}}
{{--<option value="80">Mubarak Al-Abdullah</option>--}}
{{--<option value="37">Rumaithiya</option>--}}
{{--<option value="38">Salam</option>--}}
{{--<option value="39">Salmiya</option>--}}
{{--<option value="40">Salwa</option>--}}
{{--<option value="41">Shaab</option>--}}
{{--<option value="42">Shuhada</option>--}}
{{--<option value="44">Zahra</option>--}}
{{--</optgroup>--}}

{{--<optgroup label="Ahmadi">--}}
{{--<option value="86">Abu Halifa</option>--}}
{{--<option value="87">Ahmadi</option>--}}
{{--<option value="132">Al Khiran</option>--}}
{{--<option value="134">Al Wafrah</option>--}}
{{--<option value="129">Al-Julaia&#x27;a</option>--}}
{{--<option value="133">Al-Nuwaiseeb</option>--}}
{{--<option value="88">Al-Riqqa</option>--}}
{{--<option value="124">Ali Sabah Al Salem</option>--}}
{{--<option value="89">Assabahiyah</option>--}}
{{--<option value="130">Bnaider</option>--}}
{{--<option value="90">Dahar</option>--}}
{{--<option value="91">Eqaila</option>--}}
{{--<option value="92">Fahad Al Ahmad</option>--}}
{{--<option value="93">Fahaheel</option>--}}
{{--<option value="94">Fintas</option>--}}
{{--<option value="96">Hadiya</option>--}}
{{--<option value="97">Jaber Al Ali</option>--}}
{{--<option value="98">Mahboula</option>--}}
{{--<option value="99">Mangaf</option>--}}
{{--<option value="135">Sabah Al Ahmad</option>--}}
{{--<option value="128">Shalayhat Al Dubaiya</option>--}}
{{--<option value="127">Shalayhat Mina Abdullah</option>--}}
{{--<option value="125">Shuaiba Block 1</option>--}}
{{--<option value="126">West Industrial Shuaiba</option>--}}
{{--<option value="131">Zour</option>--}}
{{--</optgroup>--}}

{{--<optgroup label="Jahra">--}}
{{--<option value="114">Al Sulaibiya Industrial 1</option>--}}
{{--<option value="115">Al Sulaibiya Industrial 2</option>--}}
{{--<option value="106">Amgarah Industrial Area</option>--}}
{{--<option value="107">Jahra</option>--}}
{{--<option value="105">Naeem</option>--}}
{{--<option value="108">Naseem</option>--}}
{{--<option value="109">Oyoun</option>--}}
{{--<option value="111">Qasr</option>--}}
{{--<option value="112">Saad Al Abdullah</option>--}}
{{--<option value="113">Sulaibiya</option>--}}
{{--<option value="116">Taima&#x27;</option>--}}
{{--<option value="117">Waha</option>--}}
{{--</optgroup>--}}