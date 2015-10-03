@extends('layout')

@section('content')
  <h1>Settings</h1>

  @if ($errors->count())
    <h2>Oops!</h2>
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  @endif

  <form method="POST" action="/settings">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <label for="title">Blog Title</label>
    <input id="title" name="title" value="{{ $title }}">

    <label for="summary">Blog Summary</label>
    <textarea id="summary" name="summary" maxlength="400">{{ $summary }}</textarea>

    <label for="postsPerPage">Number of posts per page</label>
    <input type="number" id="postsPerPage" name="postsPerPage" value="{{ $postsPerPage }}">

    <div class="checkbox">
      <input type="checkbox" id="showLoginLink" name="showLoginLink" @if ($showLoginLink) checked @endif>
      <label for="showLoginLink">Show login link</label>
    </div>

    <button type="submit">Save</button>
  </form>
@endsection