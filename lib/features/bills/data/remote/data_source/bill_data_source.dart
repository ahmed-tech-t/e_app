import 'package:dio/dio.dart';
import 'package:retrofit/retrofit.dart';
import 'package:pos_mobile/core/config/models/base_response.dart';
import 'package:pos_mobile/core/config/models/paginated_response.dart';
import 'package:pos_mobile/features/bills/data/remote/dto/bill_dto.dart';

part 'bill_data_source.g.dart';

@RestApi()
abstract class BillDataSource {
  factory BillDataSource(Dio dio, {String baseUrl}) = _BillDataSource;

  @GET('v1/sales')
  Future<BaseResponse<PaginatedData<BillDto>>> getBills({
    @Query('page') int? page,
    @Query('limit') int? limit,
  });

  @GET('v1/sales/preview')
  Future<BaseResponse<BillDto>> previewBill(@Queries() Map<String, dynamic> queries);

  @POST('v1/sales')
  @FormUrlEncoded()
  Future<BaseResponse<BillDto>> createBill(@Body() Map<String, dynamic> body);
}
