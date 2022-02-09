<?=
'<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL
?>
<rss version="2.0">
    <channel>
        <title><![CDATA[ {{ $configuracoes->nomedosite }} ]]></title>
        <link><![CDATA[ {{url('feed')}} ]]></link>
        <description><![CDATA[ {{ $configuracoes->descricao }} ]]></description>
        <language>pt-br</language>
        <pubDate>{{ now() }}</pubDate>

        @foreach($posts as $post)
            <item>
                <title><![CDATA[{{ $post->titulo }}]]></title>
                <link>{{ url('blog/artigo/'.$post->slug) }}</link>
                <image>{{ $post->cover() }}</image>
                <description><![CDATA[{!! $post->getContentWebAttribute() !!}]]></description>
                <category>{{ $post->categoriaObject->titulo }}</category>
                <author><![CDATA[{{ $post->userObject->name }}]]></author>
                <guid>{{ $post->id }}</guid>
                <pubDate>{{ $post->created_at }}</pubDate>
            </item>
        @endforeach

        @foreach($paginas as $pagina)
            <item>
                <title><![CDATA[{{ $pagina->titulo }}]]></title>
                <link>{{ url('blog/artigo/'.$pagina->slug) }}</link>
                <image>{{ $pagina->cover() }}</image>
                <description><![CDATA[{!! $pagina->getContentWebAttribute() !!}]]></description>
                <category>{{ $pagina->categoriaObject->titulo }}</category>
                <author><![CDATA[{{ $pagina->userObject->name }}]]></author>
                <guid>{{ $pagina->id }}</guid>
                <pubDate>{{ $pagina->created_at }}</pubDate>
            </item>
        @endforeach

        @foreach($projetos as $projeto)
            <item>
                <title><![CDATA[{{ $projeto->name }}]]></title>
                <link>{{ url('portifolio/'.$projeto->slug) }}</link>
                <image>{{ $projeto->cover() }}</image>
                <description><![CDATA[{!! $projeto->getContentWebAttribute() !!}]]></description>
                <category>Projetos</category>
                <author><![CDATA[ {{ $configuracoes->nomedosite }} ]]></author>
                <guid>{{ $projeto->id }}</guid>
                <pubDate>{{ $projeto->created_at }}</pubDate>
            </item>
        @endforeach        
    </channel>
</rss>