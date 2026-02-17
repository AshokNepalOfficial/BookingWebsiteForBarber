<div class="flex items-center gap-2 text-sm">
    <label class="text-gray-400">Show:</label>
    <select onchange="window.location.href = updateQueryStringParam('per_page', this.value)" 
            class="bg-dark-800 border border-white/10 text-white px-3 py-1.5 rounded focus:outline-none focus:border-gold-500 text-sm">
        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
        <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
        <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30</option>
        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
    </select>
    <span class="text-gray-400">entries</span>
</div>

<script>
function updateQueryStringParam(key, value) {
    const url = new URL(window.location);
    url.searchParams.set(key, value);
    return url.toString();
}
</script>
