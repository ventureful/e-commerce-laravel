@if ($records->lastPage() > 1)
    <div class="_w-pagination-wrapper">
        <a href="{{ $records->previousPageUrl() }}" class="button w-inline-block"
           @if($records->onFirstPage()) disabled @endif>
            <div class="button-style-w-icon"><img src="/images/chevron-left.svg" width="120" alt=""
                                                  class="b-archive-link__arrow cc-arrow-left">
                <div class="button-label">Previous</div>
            </div>
            <div class="hover-shape"></div>
        </a>
        <div class="_w-page-count">{{$records->currentPage()}}/{{$records->lastPage()}}</div>
        <a href="{{ $records->nextPageUrl() }}" class="button w-inline-block" @if(!$records->hasMorePages()) disabled @endif>
            <div class="button-style-w-icon">
                <div class="button-label">Next</div>
                <img src="/images/chevron-right.svg" width="22" alt="" class="b-archive-link__arrow cc-arrow-right">
            </div>
            <div class="hover-shape"></div>
        </a>
    </div>
@endif
