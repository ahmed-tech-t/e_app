class PaginatedData<T> {
  final List<T> items;
  final int currentPage;
  final int? totalPages;
  final int? total;

  const PaginatedData({
    required this.items,
    required this.currentPage,
    this.totalPages,
    this.total,
  });

  bool get hasMore =>
      totalPages != null ? currentPage < totalPages! : items.isNotEmpty;

  factory PaginatedData.fromJson(
    Map<String, dynamic> json,
    T Function(Object? json) fromJsonT,
  ) {
    final rawList = (json['items'] ?? json['data'] ?? []) as List;
    return PaginatedData<T>(
      items: rawList.map((x) => fromJsonT(x)).toList(),
      currentPage: json['current_page'] as int? ?? json['page'] as int? ?? 1,
      totalPages: json['last_page'] as int? ?? json['total_pages'] as int?,
      total: json['total'] as int?,
    );
  }
}
