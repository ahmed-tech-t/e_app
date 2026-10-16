import 'package:hive_flutter/hive_flutter.dart';
import 'package:pos_mobile/features/home/domain/models/Product.dart';

class ProductHiveModel {
  final int? id;
  final String? originalCode;
  final String? nameAr;
  final String? nameEn;
  final String? code;
  final String? description;
  final String? brand;
  final String? image;
  final double? retailprice;
  final double? wholeprice;
  final num? unitsPerCarton;

  ProductHiveModel({
    this.id,
    this.originalCode,
    this.nameAr,
    this.nameEn,
    this.retailprice,
    this.wholeprice,
    this.code,
    this.description,
    this.brand,
    this.image,
    this.unitsPerCarton,
  });

  // Mapper: From Domain to Hive
  factory ProductHiveModel.fromDomain(Product product) {
    return ProductHiveModel(
      id: product.id,
      originalCode: product.originalCode,
      nameAr: product.nameAr,
      nameEn: product.nameEn,
      code: product.code,
      description: product.description,
      brand: product.brand,
      image: product.image,
      retailprice: product.retailprice,
      wholeprice: product.wholeprice,
      unitsPerCarton: product.unitsPerCarton,
    );
  }

  // Mapper: From Hive to Domain
  Product toDomain() {
    return Product(
      id: id,
      originalCode: originalCode,
      nameAr: nameAr,
      nameEn: nameEn,
      code: code,
      description: description,
      brand: brand,
      image: image,
      retailprice: retailprice,
      wholeprice: wholeprice,
      unitsPerCarton: unitsPerCarton,
    );
  }
}

class ProductHiveModelAdapter extends TypeAdapter<ProductHiveModel> {
  @override
  final int typeId = 0;

  @override
  ProductHiveModel read(BinaryReader reader) {
    final numOfFields = reader.readByte();
    final fields = <int, dynamic>{
      for (int i = 0; i < numOfFields; i++) reader.readByte(): reader.read(),
    };
    return ProductHiveModel(
      originalCode: fields[0] as String?,
      nameAr: fields[1] as String?,
      nameEn: fields[2] as String?,
      code: fields[3] as String?,
      description: fields[4] as String?,
      brand: fields[5] as String?,
      image: fields[6] as String?,
      retailprice: (fields[7] as num?)?.toDouble(),
      wholeprice: (fields[8] as num?)?.toDouble(),
      unitsPerCarton: fields[9] as num?,
      id: fields[10] as int?,
    );
  }

  @override
  void write(BinaryWriter writer, ProductHiveModel obj) {
    writer
      ..writeByte(11)
      ..writeByte(0)
      ..write(obj.originalCode)
      ..writeByte(1)
      ..write(obj.nameAr)
      ..writeByte(2)
      ..write(obj.nameEn)
      ..writeByte(3)
      ..write(obj.code)
      ..writeByte(4)
      ..write(obj.description)
      ..writeByte(5)
      ..write(obj.brand)
      ..writeByte(6)
      ..write(obj.image)
      ..writeByte(7)
      ..write(obj.retailprice)
      ..writeByte(8)
      ..write(obj.wholeprice)
      ..writeByte(9)
      ..write(obj.unitsPerCarton)
      ..writeByte(10)
      ..write(obj.id);
  }

  @override
  int get hashCode => typeId.hashCode;

  @override
  bool operator ==(Object other) =>
      identical(this, other) ||
      other is ProductHiveModelAdapter &&
          runtimeType == other.runtimeType &&
          typeId == other.typeId;
}
