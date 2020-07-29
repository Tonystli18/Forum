@extends('layouts.app')

@section('content')
<div class="container">
    <algolia-search inline-template>
        <ais-instant-search
        :search-client="searchClient"
        index-name="threads"
        :routing="routing"
        >
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <ais-hits>
                        <ul slot-scope="{ items }">
                          <li v-for="item in items" :key="item.objectID">
                            <a :href="item.path">
                                <ais-highlight
                                    :hit="item"
                                    attribute="title"
                                />
                            </a>
                            <p>
                                <ais-highlight
                                    :hit="item"
                                    attribute="body"
                                />
                            </p>
                          </li>
                        </ul>
                    </ais-hits>
                    <div class="pagination mt-4"><ais-pagination /></div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Search
                        </div>
                        <div class="card-body">
                        <ais-configure query="{{request('query')}}"></ais-configure>
                        <ais-search-box placeholder="Search threads...">
                        </ais-search-box>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            Refinement
                        </div>
                        <div class="card-body">
                            <ais-refinement-list attribute="channel.name" />
                        </div>
                    </div>
                    @if(count($trending))
                    <div class="card">
                        <div class="card-header">
                            Trending Threads
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                @foreach ($trending as $thread)
                                <a href="{{url($thread->path)}}">
                                <li class="list-group-item"> {{ $thread-> title}}</li>
                                </a>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </ais-instant-search>
    </algolia-search>
</div>
@endsection