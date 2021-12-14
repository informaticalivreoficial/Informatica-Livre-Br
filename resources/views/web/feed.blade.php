<?=
'<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL
?>
<rss version="2.0">
    <channel>
        <title><![CDATA[ {{ $Configuracoes->nomedosite }} ]]></title>
        <link><![CDATA[ {{url('feed')}} ]]></link>
        <description><![CDATA[ {{ $Configuracoes->descricao }} ]]></description>
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

        @foreach($roteiros as $roteiro)
            <item>
                <title><![CDATA[{{ $roteiro->name }}]]></title>
                <link>{{ url('roteiro/'.$roteiro->slug) }}</link>
                <image>{{ $roteiro->cover() }}</image>
                <description><![CDATA[{!! $roteiro->getContentWebAttribute() !!}]]></description>
                <category>Passeios</category>
                <author><![CDATA[ {{ $Configuracoes->nomedosite }} ]]></author>
                <guid>{{ $roteiro->id }}</guid>
                <pubDate>{{ $roteiro->created_at }}</pubDate>
            </item>
        @endforeach        
    </channel>
</rss>