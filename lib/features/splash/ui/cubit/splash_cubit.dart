import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:freezed_annotation/freezed_annotation.dart';
import 'package:pos_mobile/features/home/domain/repository/product_repository.dart';

part 'splash_state.dart';
part 'splash_cubit.freezed.dart';

class SplashCubit extends Cubit<SplashState> {
  final ProductRepository _productRepository;

  SplashCubit(this._productRepository) : super(const SplashState.initial());

  Future<void> syncProducts() async {
    emit(const SplashState.loading());
    try {
      await _productRepository.getProducts();
      emit(const SplashState.success());
    } catch (e) {
      emit(SplashState.error(e.toString()));
    }
  }
}
