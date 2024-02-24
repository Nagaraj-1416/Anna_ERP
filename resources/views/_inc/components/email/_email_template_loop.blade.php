<table style="width: 100%; background: #F5F5F5; border-collapse: collapse; margin: 10px 0; border: 1px solid #e0e0e0;">
    <thead style="background: #E0E0E0;">
    <tr>
        @foreach ($loopMeta['fields'] as $field => $data)
            @if ($data['enabled'] != 'true')
                @continue
            @endif
            <th style="padding: 10px; text-align: left;">{{ $data['heading'] }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody style="text-align: center;">
    @foreach ($collection as $item)
        <tr>
            @foreach ($loopMeta['fields'] as $field => $data)
                @php $value = $item @endphp
                @if ($data['enabled'] != 'true')
                    @continue
                @endif
                @foreach ($data['attributes'] as $attribute)
                    @php $attributeName = $attribute['attribute'] @endphp
                    @if ($attribute['type'] == 'object')
                        @php $value = $value->$attributeName @endphp
                    @elseif ($attribute['type'] == 'array')
                        @php $value = $value[$attributeName]  @endphp
                    @endif
                @endforeach
                <td style="padding: 10px; text-align: left;">{{ $value }}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>