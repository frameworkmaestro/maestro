--
-- Remoção do layerType CEFE
--
delete from label
where idLayer in (
select idLayer from Layer where idLayerType in (
select idLayerType from LayerType where entry = 'lty_cefe')
);

delete from Layer
where idLayerType in (
select idLayerType from LayerType where entry = 'lty_cefe'
);

