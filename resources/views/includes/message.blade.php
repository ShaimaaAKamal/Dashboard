@if (session()->has('success'))
       {!! session()->get('success') !!}
@elseif (session()->has('error'))
        {!! session()->get('error')  !!}
@endif
