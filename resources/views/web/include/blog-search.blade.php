<div>
    <h5 class="text-bold">Pesquisar no Blog</h5>
</div>
<div class="offset-top-6">
    <div class="text-subline bg-pizazz"></div>
</div>
<div class="offset-top-15 offset-md-top-20 rd-search-blog">
    <!-- RD Search Form-->
    <form action="{{route('web.blog.searchBlog')}}" method="post" class="form-search">
        @csrf
        <div class="form-group">
            <input type="text" name="filter" value="{{ $filters['filter'] ?? '' }}" class="form-search-input form-control">
        </div>
        <button type="submit" class="form-search-submit"><span class="fa fa-search"></span></button>
    </form>
</div>