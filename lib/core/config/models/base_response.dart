// lib/core/network/models/base_response.dart
import 'package:pos_mobile/core/config/models/paginated_response.dart';

class BaseResponse<T> {
  final int? code;
  final String status;
  final String? message;
  final T? data;

  const BaseResponse({
    this.code,
    required this.status,
    this.message,
    this.data,
  });

  factory BaseResponse.fromJson(
    Map<String, dynamic> json,
    T Function(Object? json)? fromJsonT,
  ) {
    return BaseResponse<T>(
      code: json['code'] as int? ?? 404,
      status: json['status'] as String? ?? 'error',
      message: json['message'] as String?,
      data: json['data'] != null && fromJsonT != null
          ? fromJsonT(json['data'])
          : null,
    );
  }

  static BaseResponse<PaginatedData<T>> paginatedFromJson<T>(
    Map<String, dynamic> json,
    T Function(Map<String, dynamic> item) itemParser,
  ) {
    return BaseResponse<PaginatedData<T>>.fromJson(
      json,
      (dataJson) => PaginatedData<T>.fromJson(
        dataJson as Map<String, dynamic>,
        (itemJson) => itemParser(itemJson as Map<String, dynamic>),
      ),
    );
  }
}
