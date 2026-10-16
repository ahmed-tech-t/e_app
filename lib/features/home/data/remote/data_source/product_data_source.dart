import 'package:dio/dio.dart';
import 'package:retrofit/retrofit.dart';
import 'package:pos_mobile/core/config/models/base_response.dart';
import 'package:pos_mobile/features/home/data/remote/dto/product_dto.dart';

part 'product_data_source.g.dart';

@RestApi()
abstract class ProductDataSource {
  factory ProductDataSource(Dio dio, {String baseUrl}) = _ProductDataSource;

  @GET('v1/products')
  Future<BaseResponse<List<ProductDto>>> getProducts();
}
