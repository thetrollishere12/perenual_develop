@section('title')

@endsection
<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sandbox') }}
        </h2>
    </x-slot>

    <div class="md:flex">
        <x-profile-nav>
            <x-slot name="url">sandbox</x-slot>
        </x-profile-nav>

        <div>
            <table class="text-xs">
            @foreach($datas as $key => $data)
            @if($key == 0)
                <tr>
                <th>{{ $data[0] }}</th>
                <th>Site Link</th>
                <th>{{ $data[2] }}</th>
                <th>{{ $data[3] }}</th>
                <th>{{ $data[4] }}</th>
                <th>{{ $data[5] }}</th>
                <th>{{ $data[6] }}</th>
                <th>{{ $data[7] }}</th>
                <th>{{ $data[8] }}</th>
                <th>{{ $data[9] }}</th>
                <th>{{ $data[10] }}</th>
                <th>{{ $data[11] }}</th>
                <th>{{ $data[12] }}</th>
                <th>{{ $data[13] }}</th>
                <th>{{ $data[14] }}</th>
                <th>{{ $data[15] }}</th>
              </tr>
            @else
              <tr>
                <th>{{ $data[0] }}</th>
                <th><a href="{{ $data[1] }}">Link</a></th>
                <th>{{ $data[2] }}</th>
                <th>{{ $data[3] }}</th>
                <th>{{ $data[4] }}</th>
                <th>{{ $data[5] }}</th>
                <th>{{ $data[6] }}</th>
                <th>{{ $data[7] }}</th>
                <th>{{ $data[8] }}</th>
                <th><a href="{{ $data[9] }}">Link</a></th>
                <th><a href="{{ $data[10] }}">Link</a></th>
                <th><a href="{{ $data[11] }}">Link</a></th>
                <th><a href="{{ $data[12] }}">Link</a></th>
                <th>{{ $data[13] }}</th>
                <th>{{ $data[14] }}</th>
                <th>{{ $data[15] }}</th>
              </tr>
            @endif
            @endforeach
            </table>
        </div>


<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>

    </div>

</x-guest-layout>
