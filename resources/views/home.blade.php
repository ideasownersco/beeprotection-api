@extends('layouts.app')

<script>

  function ascii_to_hexa(str) {
    var arr = [];
    for (var n = 0, l = str.length; n < l; n++) {
      var hex = Number(str.charCodeAt(n)).toString(16);
      arr.push(hex);
    }
    return arr.join('');
  }

  function isalpha(char) {
    return char.toUpperCase() != char.toLowerCase();
  }

  function shift(str) {
    var n = '';
    var alphs = 'abcdefghijklmnopqrstuvwxyz';
    var digit = '1234567890';
    for (var i = 0; i < str.length; i++) {
      var x = str.charAt(i);
      if (isalpha(x)) {
        x = x.toLowerCase();
        n = n.concat(alphs[(alphs.indexOf(x) + 3) % 26]);
      } else if (!isNaN(i)) {
        n = n.concat(digit[(digit.indexOf(x) + 8) % 10]);
      }
    }
    return n;
  }

  // console.log('Decode the following to get the flag');
  // console.log(
  //   "var code = 'NDc0aDQxNTI0NDRkNWUzMzU4NTExOTQyNDMzaTQyMTg1NTRoM2k0ZDI4NTQyODUxNDE1MDE5NTg1MjVn'",
  // );

  var code = btoa(shift(ascii_to_hexa('NDc0aDQxNTI0NDRkNWUzMzU4NTExOTQyNDMzaTQyMTg1NTRoM2k0ZDI4NTQyODUxNDE1MDE5NTg1MjVn')));

  console.log(code);

</script>

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!<a href="{{route('admin.home')}}">Click here to go to dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
