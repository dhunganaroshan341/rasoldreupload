  {{-- Pagination links --}}
  <nav>
      <ul class="pagination">
          @if ($paginatedTransactions->onFirstPage())
              <li class="page-item disabled">
                  <span class="page-link">Previous</span>
              </li>
          @else
              <li class="page-item">
                  <a class="page-link" href="{{ $paginatedTransactions->previousPageUrl() }}" rel="prev">Previous</a>
              </li>
          @endif

          @if ($paginatedTransactions->hasMorePages())
              <li class="page-item">
                  <a class="page-link" href="{{ $paginatedTransactions->nextPageUrl() }}" rel="next">Next</a>
              </li>
          @else
              <li class="page-item disabled">
                  <span class="page-link">Next</span>
              </li>
          @endif

      </ul>
      <p>Total Pages: {{ $totalPages }}</p>
  </nav>
