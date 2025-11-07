<div style="font-family: Arial, Helvetica, sans-serif; line-height: 1.4;">
    <h3>A document has been shared with you</h3>

    <p>
        @if ($share->document)
            Document: <strong>{{ $share->document->name }}</strong><br>
        @elseif($share->signedDocument)
            Signed Document: <strong>{{ $share->signedDocument->label }}</strong><br>
        @endif
    </p>

    <p>
        You can view the document using the link below:
    </p>

    <p>
        <a href="{{ $share->getShareUrl() }}">Open Document</a>
    </p>

    @if (!empty($share->metadata['message']))
        <hr>
        <p>{{ $share->metadata['message'] }}</p>
    @endif

    <p style="font-size: 0.9rem; color: #666;">If you did not expect this email, you can ignore it.</p>
</div>
