import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:freezed_annotation/freezed_annotation.dart';
import 'package:pos_mobile/features/home/domain/models/Product.dart';
import 'package:pos_mobile/features/home/domain/repository/product_repository.dart';

part 'home_state.dart';
part 'home_cubit.freezed.dart';

class HomeCubit extends Cubit<HomeState> {
  final ProductRepository _productRepository;

  HomeCubit(this._productRepository) : super(const HomeState.initial());

  Future<void> getProducts({int? page, int? limit}) async {
    emit(const HomeState.loading());
    try {
      final products = await _productRepository.getProducts();
      emit(HomeState.loaded(products));
    } catch (e) {
      emit(HomeState.error(e.toString()));
    }
  }

  void getCachedProducts() {
    emit(const HomeState.loading());
    try {
      final products = _productRepository.getCachedProducts();
      emit(HomeState.loaded(products));
    } catch (e) {
      emit(HomeState.error(e.toString()));
    }
  }
}
