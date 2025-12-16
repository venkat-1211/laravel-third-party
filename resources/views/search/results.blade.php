<form method="GET" action="{{ route('search') }}">
  <input name="q" value="{{ request('q') }}" placeholder="Search products">
  <button>Search</button>
</form>

<h3>Results for "{{ $q }}"</h3>
<ul>
@foreach($results as $p)
  <li>{{ $p->title }} — ₹{{ number_format($p->price,2) }}</li>
@endforeach
</ul>
